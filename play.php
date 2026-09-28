<?php
include('./conn.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$levelNumber = isset($_GET['level']) ? (int)$_GET['level'] : 1;

// Fetch the level
$stmt = $conn->prepare("SELECT * FROM typing_levels WHERE level_number = ?");
$stmt->execute([$levelNumber]);
$level = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$level) {
    die("Level not found.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Typing Game - Level <?= $levelNumber ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #0f172a;
            color: white;
            font-family: 'Poppins', sans-serif;
        }
        #codeDisplay {
            background-color: #1e293b;
            padding: 15px;
            border-radius: 5px;
            white-space: pre-wrap;
            font-family: monospace;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h2>Level <?= htmlspecialchars($level['level_number']) ?></h2>
    <p class="text-muted">Type the code exactly as shown below:</p>

    <div id="codeDisplay"><?= htmlspecialchars($level['code_text']) ?></div>

    <textarea id="userInput" class="form-control mt-4" rows="8" placeholder="Start typing here..." disabled></textarea>

    <div class="mt-3">
        <strong>Time Left:</strong> <span id="timer"><?= $level['time_limit'] ?></span>s<br>
        <strong>Accuracy:</strong> <span id="accuracy">0%</span>
    </div>

    <button class="btn btn-success mt-3" onclick="startGame()">▶️ Start</button>
</div>

<!-- Modal -->
<div class="modal fade" id="resultModal" tabindex="-1" aria-labelledby="resultModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content text-dark">
      <div class="modal-header bg-dark text-white">
        <h5 class="modal-title" id="resultModalLabel">🎉 Typing Result</h5>
      </div>
      <div class="modal-body">
        <p>✅ Accuracy: <strong><span id="modalAccuracy">0%</span></strong></p>
        <p>💰 Reward: <strong>₱<span id="modalCoins">0.00</span></strong></p>
        <input type="hidden" id="finalAccuracy">
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary" onclick="submitScore()">Submit Score</button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const code = `<?= addslashes($level['code_text']) ?>`;
    const totalTime = <?= (int)$level['time_limit'] ?>;
    const coinReward = <?= (float)$level['coin_reward'] ?>;
    const levelNumber = <?= (int)$level['level_number'] ?>;
    let timeLeft = totalTime;
    let timer;

    const inputField = document.getElementById('userInput');
    const timerDisplay = document.getElementById('timer');
    const accuracyDisplay = document.getElementById('accuracy');

    function startGame() {
        inputField.disabled = false;
        inputField.focus();
        document.querySelector('button').disabled = true;

        timer = setInterval(() => {
            timeLeft--;
            timerDisplay.textContent = timeLeft;
            if (timeLeft <= 0) {
                clearInterval(timer);
                endGame();
            }
        }, 1000);
    }

    function endGame() {
        clearInterval(timer);
        inputField.disabled = true;

        const typed = inputField.value.trim();
        const expected = code.trim();
        let correct = 0;

        for (let i = 0; i < typed.length; i++) {
            if (typed[i] === expected[i]) correct++;
        }

        const accuracy = expected.length ? ((correct / expected.length) * 100).toFixed(2) : 0;
        accuracyDisplay.textContent = `${accuracy}%`;

        // Show modal
        document.getElementById('modalAccuracy').textContent = `${accuracy}%`;
        document.getElementById('modalCoins').textContent = coinReward.toFixed(2);
        document.getElementById('finalAccuracy').value = accuracy;

        const resultModal = new bootstrap.Modal(document.getElementById('resultModal'));
        resultModal.show();
    }

    function submitScore() {
        const accuracy = document.getElementById('finalAccuracy').value;

        fetch('submit_score.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({
                level: levelNumber,
                accuracy: accuracy,
                time_left: timeLeft
            })
        })
        .then(res => res.json())
        .then(data => {
            alert(data.message || 'Score submitted!');
            window.location.href = 'type_to_earn.php';
        })
        .catch(err => {
            console.error(err);
            alert("Failed to submit score.");
        });
    }
</script>

</body>
</html>

<?php $conn = null; ?>
