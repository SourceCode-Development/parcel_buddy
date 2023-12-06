<?php
include('components/head.php');

include('components/sidebar.php');
?>

<div class="content-body container">
    <?php
    use app\core\Application;
    if (Application::$app->session->getFlash('success')): ?>
        <div class="alert alert-success">
            <p><?php echo Application::$app->session->getFlash('success') ?></p>
        </div>
    <?php endif; ?>
    {{content}}
</div>

<?php
include('components/footer.php');
?>