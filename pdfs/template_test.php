<?php
// Test file for viewing invoices with different templates
?>
<!DOCTYPE html>
<html>
<head>
    <title>Invoice Template Test</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1 { color: #333; }
        .link-box { 
            margin: 10px 0; 
            padding: 15px; 
            border: 1px solid #ddd; 
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        a { 
            display: inline-block;
            margin: 5px 0;
            padding: 8px 15px;
            background-color: #0066cc;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }
        a:hover { background-color: #004d99; }
    </style>
</head>
<body>
    <h1>Invoice Template Test Links</h1>
    <p>Click on the links below to test different invoice templates:</p>
    
    <div class="link-box">
        <h2>Revision Alpha Templates</h2>
        <a href="index.php?test=1&template=revisionalpha/30716710072_01_0001.php">Factura A (01)</a>
        <a href="index.php?test=1&template=revisionalpha/30716710072_02_0001.php">Nota de Débito A (02)</a>
        <a href="index.php?test=1&template=revisionalpha/30716710072_03_0001.php">Nota de Crédito A (03)</a>
        <a href="index.php?test=1&template=revisionalpha/30716710072_06_0001.php">Factura B (06)</a>
        <a href="index.php?test=1&template=revisionalpha/30716710072_07_0001.php">Nota de Débito B (07)</a>
        <a href="index.php?test=1&template=revisionalpha/30716710072_08_0001.php">Nota de Crédito B (08)</a>
    </div>
    
    <div class="link-box">
        <h2>Pedimos Facil Templates</h2>
        <a href="index.php?test=1&template=pedimosfacil/30716710072_01_0001.php">Factura A (01)</a>
        <a href="index.php?test=1&template=pedimosfacil/30716710072_03_0001.php">Nota de Crédito A (03)</a>
        <a href="index.php?test=1&template=pedimosfacil/30716710072_06_0001.php">Factura B (06)</a>
        <a href="index.php?test=1&template=pedimosfacil/30716710072_08_0001.php">Nota de Crédito B (08)</a>
    </div>
</body>
</html> 