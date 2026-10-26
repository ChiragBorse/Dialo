<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login-signup.html#login");
    exit;
}
$userName = htmlspecialchars($_SESSION['user_name']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<title>Dialo – Chat</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:wght@600&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
:root{
  --cream:#fbf4e9; --card:#ffffff; --ink:#2c1e14; --muted:#7a6a5a;
  --orange:#e8713a; --olive:#90a861; --brown:#2b2018; --line:rgba(43,32,24,.12);
  --chip:#f3e9d8; --shadow:0 20px 50px -20px rgba(60,40,20,.3);
  --serif:"Fraunces",Georgia,serif; --sans:"Plus Jakarta Sans",system-ui,sans-serif;
  box-sizing:border-box;
}
*{box-sizing:inherit}
body{margin:0;min-height:100svh;background:var(--cream);color:var(--ink);font-family:var(--sans);display:flex;flex-direction:column}
header{display:flex;align-items:center;justify-content:space-between;padding:18px 28px;border-bottom:1px solid var(--line);background:var(--card)}
.brand{display:flex;align-items:center;gap:10px;font-family:var(--serif);font-size:20px;font-weight:600}
.gem{width:26px;height:26px;border-radius:50%;background:conic-gradient(var(--orange),var(--olive),var(--orange));}
.who{font-size:14px;color:var(--muted)}
.logout{padding:9px 16px;border-radius:999px;border:1px solid var(--line);background:var(--card);color:var(--ink);font-weight:600;font-size:13.5px;cursor:pointer;text-decoration:none}
.logout:hover{border-color:var(--orange)}
main{flex:1;display:flex;flex-direction:column;max-width:720px;width:100%;margin:0 auto;padding:30px 20px 100px}
.notice{background:var(--chip);color:#7c5a2e;border-radius:14px;padding:14px 18px;font-size:14.5px;margin-bottom:24px;text-align:center}
.msgs{flex:1;display:flex;flex-direction:column;gap:14px}
.msg{max-width:78%;padding:13px 18px;border-radius:18px;font-size:15px;line-height:1.5;opacity:0;animation:pop .35s ease forwards}
@keyframes pop{from{opacity:0;transform:translateY(8px)}to{opacity:1;transform:none}}
.msg.ai{background:var(--card);border:1px solid var(--line);border-bottom-left-radius:4px;align-self:flex-start}
.msg.me{background:var(--olive);color:#fff;border-bottom-right-radius:4px;align-self:flex-end}
.typing{display:inline-flex;gap:5px;padding:12px 14px}
.typing span{width:6px;height:6px;border-radius:50%;background:var(--orange);opacity:.4;animation:bounce 1s infinite}
.typing span:nth-child(2){animation-delay:.15s}.typing span:nth-child(3){animation-delay:.3s}
@keyframes bounce{40%{transform:translateY(-5px);opacity:1}}
form.inputbar{position:fixed;bottom:0;left:0;right:0;background:var(--cream);border-top:1px solid var(--line);padding:16px}
.inputbar .row{max-width:720px;margin:0 auto;display:flex;gap:10px}
.inputbar input{flex:1;padding:14px 18px;border-radius:999px;border:1.5px solid var(--line);background:var(--card);color:var(--ink);font-size:15px}
.inputbar input:focus{outline:none;border-color:var(--orange)}
.inputbar button{width:48px;height:48px;border-radius:50%;border:0;background:var(--orange);color:#fff;font-size:18px;cursor:pointer;transition:transform .2s}
.inputbar button:hover{transform:scale(1.07)}
</style>
</head>
<body>
<header>
  <a href="#" class="brand"><span class="gem"></span>Dialo</a>
  <div style="display:flex;align-items:center;gap:16px">
    <span class="who">Signed in as <b><?php echo $userName; ?></b></span>
    <a href="logout.php" class="logout">Log out</a>
  </div>
</header>

<main>
  <div class="notice">✅ Login/signup worked — this page only loads because your session is active.</div>
  <div class="msgs" id="msgs">
    <div class="msg ai">Hi <?php echo $userName; ?>, I'm Dialo. This is just a test chat screen to confirm your account is wired up correctly — type anything below.</div>
  </div>
</main>

<form class="inputbar" id="form">
  <div class="row">
    <input type="text" id="input" placeholder="Type a test message…" autocomplete="off" required>
    <button type="submit">↑</button>
  </div>
</form>

<script>
const msgs=document.getElementById('msgs'),form=document.getElementById('form'),input=document.getElementById('input');
function add(cls,text){const d=document.createElement('div');d.className='msg '+cls;d.textContent=text;msgs.appendChild(d);d.scrollIntoView({behavior:'smooth',block:'end'});return d;}
form.addEventListener('submit',e=>{
  e.preventDefault();
  const v=input.value.trim(); if(!v)return;
  add('me',v); input.value='';
  const t=add('ai',''); t.innerHTML='<span class="typing"><span></span><span></span><span></span></span>';
  setTimeout(()=>{ t.innerHTML=''; t.textContent="This is a placeholder reply — the real AI model connects in a later phase."; t.scrollIntoView({behavior:'smooth'}); },900);
});
</script>
</body>
</html>
