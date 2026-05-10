<?php if (isset($_SESSION['alert'])): ?>

    <?php
        $type = $_SESSION['alert']['type'];
        $message = $_SESSION['alert']['message'];

        $bgColor = '#2563eb';
        $icon = 'ℹ️';

        switch ($type) {
            case 'success':
                $bgColor = '#16a34a';
                $icon = '✅';
                break;

            case 'error':
                $bgColor = '#dc2626';
                $icon = '❌';
                break;

            case 'warning':
                $bgColor = '#f59e0b';
                $icon = '⚠️';
                break;

            case 'info':
                $bgColor = '#2563eb';
                $icon = 'ℹ️';
                break;
        }
    ?>

    <!-- ALERT HTML -->
    <div id="customAlert" class="custom-alert">
        <div class="alert-content" style="background: <?= $bgColor ?>;">
            <span class="alert-icon"><?= $icon ?></span>
            <span class="alert-message"><?= htmlspecialchars($message) ?></span>
            <button class="alert-close" onclick="closeAlert()">×</button>
        </div>
    </div>

    <!-- STYLE -->
    <style>
        .custom-alert {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            animation: slideIn 0.3s ease;
        }

        .alert-content {
            min-width: 300px;
            max-width: 400px;
            color: white;
            padding: 14px 18px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            font-family: Arial, sans-serif;
        }

        .alert-icon {
            font-size: 20px;
        }

        .alert-message {
            flex: 1;
            font-size: 14px;
        }

        .alert-close {
            background: transparent;
            border: none;
            color: white;
            font-size: 20px;
            cursor: pointer;
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes fadeOut {
            from {
                opacity: 1;
            }
            to {
                opacity: 0;
            }
        }
    </style>

    <!-- SCRIPT -->
    <script>
        function closeAlert() {
            const alertBox = document.getElementById('customAlert');
            alertBox.style.animation = 'fadeOut 0.3s ease';

            setTimeout(() => {
                alertBox.remove();
            }, 300);
        }

        setTimeout(() => {
            const alertBox = document.getElementById('customAlert');

            if (alertBox) {
                alertBox.style.animation = 'fadeOut 0.3s ease';

                setTimeout(() => {
                    alertBox.remove();
                }, 300);
            }
        }, 4000);
    </script>

    <?php unset($_SESSION['alert']); ?>

<?php endif; ?>