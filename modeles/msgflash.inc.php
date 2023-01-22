<?php

function set_flash($message, $type) {
    $_SESSION['flash'] = array(
        'message' => $message,
        'type' => $type
    );
}

function print_flash() {
    if (!empty($_SESSION['flash'])) {
        echo '<div class="alert alert-'.$_SESSION['flash']['type'].' alert-dismissible fade show" role="alert">
        '.$_SESSION['flash']['message'].'
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
        unset($_SESSION['flash']);
    }
}