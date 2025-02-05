<?php
class SecureSessionHandler extends SessionHandler {
    private $key;

    public function __construct() {
        $this->key = hash('sha256', $env['SESSION_KEY'] ?? 'default_key');
    }

    public function read($id) {
        $data = parent::read($id);
        return $data ? sodium_crypto_secretbox_open(
            base64_decode($data),
            random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES),
            $this->key
        ) : '';
    }

    public function write($id, $data) {
        $nonce = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
        $encrypted = sodium_crypto_secretbox($data, $nonce, $this->key);
        return parent::write($id, base64_encode($encrypted));
    }
}

// Initialize secure session handling
$handler = new SecureSessionHandler();
session_set_save_handler($handler, true); 