<?php include 'header.php'; ?>

<div class="error-container" style="max-width: 600px; margin: 50px auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px; background-color: #f9f9f9; text-align: center;">
    <h2 style="color: #d9534f;">Ha ocurrido un error</h2>
    
    <!-- Verificamos si existe un mensaje de error en la URL (GET) -->
    <?php if (isset($_GET['msg'])): ?>
        <div class="alert" style="background-color: #fdf2ce; color: #8a6d3b; padding: 15px; margin-bottom: 20px; border-radius: 4px; border: 1px solid #faebcc;">
            <!-- Mostramos el mensaje de forma segura usando htmlspecialchars -->
            <?php echo htmlspecialchars($_GET['msg']); ?>
        </div>
    <?php else: ?>
        <div class="alert" style="background-color: #fdf2ce; color: #8a6d3b; padding: 15px; margin-bottom: 20px; border-radius: 4px; border: 1px solid #faebcc;">
            Error desconocido.
        </div>
    <?php endif; ?>
    
    <div class="actions">
        <a href="javascript:history.back()" class="btn" style="text-decoration: none; color: #337ab7; margin-right: 15px;">&larr; Volver atrás</a>
        <a href="index.php" class="btn btn-primary" style="text-decoration: none; color: #337ab7;">Ir al Inicio</a>
    </div>
</div>

<?php include 'footer.php'; ?>