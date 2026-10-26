<?php
// Receives the conversation so far from chat.html, sends it to the
// Gemini API using the key from api_config.php, and returns Dialo's reply.
header("Content-Type: application/json");
require __DIR__ . "/config.php";     // for session_start() + $conn (so only logged-in users can chat)
require __DIR__ . "/api_config.php"; // for the API key

if (empty($_SESSION["user_id"])) {
    http_response_code(401);
    echo json_encode(["ok" => false, "message" => "Please log in first."]);
    exit;
}

if (GEMINI_API_KEY === 'paste-your-api-key-here' || GEMINI_API_KEY === '') {
    echo json_encode(["ok" => false, "message" => "No API key set yet. Open api_config.php and paste your Gemini API key in."]);
    exit;
}

$data     = json_decode(file_get_contents("php://input"), true);
$messages = $data["messages"] ?? [];

if (!is_array($messages) || count($messages) === 0) {
    echo json_encode(["ok" => false, "message" => "No message received."]);
    exit;
}

// Convert messages to Gemini format (role: "user"/"model", parts: [{text: "..."}])
$contents = [];
foreach (array_slice($messages, -20) as $m) {
    if (isset($m["role"], $m["content"]) && in_array($m["role"], ["user", "assistant"], true)) {
        $geminiRole = $m["role"] === "assistant" ? "model" : "user";
        $contents[] = [
            "role"  => $geminiRole,
            "parts" => [["text" => (string)$m["content"]]]
        ];
    }
}

// Prepend a system context turn if the first message is from the user
// (some models don't support system_instruction field, so we inject it inline)
if (!empty($contents) && $contents[0]["role"] === "user") {
    $contents[0]["parts"][0]["text"] =
        "You are Dialo, a friendly, clear and helpful AI chat assistant. " .
        "Keep replies conversational and easy to read.\n\n" .
        $contents[0]["parts"][0]["text"];
}

$payload = [
    "contents"         => $contents,
    "generationConfig" => ["maxOutputTokens" => 1024],
];

$apiKey = GEMINI_API_KEY;

// Fallback chain — confirmed working on this API key (tested live)
$modelFallbacks = [
    GEMINI_MODEL,           // primary: gemini-flash-lite-latest (confirmed working)
    'gemini-3.6-flash',
    'gemini-3.5-flash',
];

$reply        = "";
$lastError    = "The AI service returned an error.";
$succeeded    = false;
$quotaHit     = false;   // true when ALL models returned 429
$quotaModels  = 0;       // count of models that hit quota

foreach ($modelFallbacks as $model) {
    $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}";

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => json_encode($payload),
        CURLOPT_HTTPHEADER     => ["Content-Type: application/json"],
        CURLOPT_TIMEOUT        => 60,
    ]);
    $response = curl_exec($ch);
    $curlErr  = curl_error($ch);
    $status   = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($curlErr) {
        $lastError = "Could not reach the AI service: $curlErr";
        continue;
    }

    $json = json_decode($response, true);

    if ($status !== 200) {
        $errMsg    = $json["error"]["message"] ?? "HTTP $status error.";
        $lastError = $errMsg;

        if ($status === 429) {
            $quotaModels++;
            continue; // try next model
        }
        if (in_array($status, [503, 404])) {
            continue; // overloaded / not found — try next
        }
        break; // auth / bad-request — no point retrying
    }

    // Extract reply text
    foreach (($json["candidates"] ?? []) as $candidate) {
        foreach (($candidate["content"]["parts"] ?? []) as $part) {
            if (isset($part["text"])) {
                $reply .= $part["text"];
            }
        }
    }

    if ($reply !== "") {
        $succeeded = true;
        break;
    }

    $lastError = "Got an empty reply (model: $model).";
}

// ── Quota exceeded: all models returned 429 ─────────────────────────────────
if (!$succeeded && $quotaModels === count($modelFallbacks)) {
    // Google free-tier daily quota resets at midnight Pacific (UTC-8 / UTC-7 DST).
    // We calculate the next midnight UTC-8 from now and present it in a friendly way.
    $nowUtc        = new DateTimeImmutable('now', new DateTimeZone('UTC'));
    $pacificTz     = new DateTimeZone('America/Los_Angeles');
    $nowPacific    = $nowUtc->setTimezone($pacificTz);

    // Next midnight Pacific
    $resetPacific  = (new DateTimeImmutable(
        $nowPacific->format('Y-m-d') . ' 00:00:00',
        $pacificTz
    ))->modify('+1 day');

    // Convert reset time to user-friendly UTC string and also get seconds away
    $resetUtc      = $resetPacific->setTimezone(new DateTimeZone('UTC'));
    $secondsLeft   = $resetPacific->getTimestamp() - $nowUtc->getTimestamp();
    $hoursLeft     = floor($secondsLeft / 3600);
    $minsLeft      = floor(($secondsLeft % 3600) / 60);

    $resetLabel    = $resetUtc->format('D, d M Y \a\t H:i') . ' UTC';

    if ($hoursLeft > 0) {
        $timeAway = "{$hoursLeft}h {$minsLeft}m";
    } else {
        $timeAway = "{$minsLeft} minute" . ($minsLeft !== 1 ? 's' : '');
    }

    echo json_encode([
        "ok"            => false,
        "quota_exceeded" => true,
        "reset_label"   => $resetLabel,
        "time_away"     => $timeAway,
        "message"       => "Daily API quota reached. Resets in {$timeAway} ({$resetLabel}).",
    ]);
    exit;
}
// ────────────────────────────────────────────────────────────────────────────

if (!$succeeded || $reply === "") {
    echo json_encode(["ok" => false, "message" => $lastError]);
    exit;
}

echo json_encode(["ok" => true, "reply" => $reply]);
