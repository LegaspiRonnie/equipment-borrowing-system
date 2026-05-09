<?php
$pageTitle = $pageTitle ?? 'Equipment Borrowing System';
$assetPath = $assetPath ?? 'assets';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle, ENT_QUOTES); ?></title>
    <link rel="stylesheet" href="<?php echo $assetPath; ?>/css/global.css">
    <link rel="stylesheet" href="<?php echo $assetPath; ?>/css/theme.css">
    <?php if (!empty($extraHead)) echo $extraHead; ?>
</head>
<body>
