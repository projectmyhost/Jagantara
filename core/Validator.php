<?php

class Validator {
    private array $errors = [];
    private array $data = [];

    public function __construct(array $data) {
        $this->data = $data;
    }

    public function required(string $field, string $label): static {
        $value = trim($this->data[$field] ?? '');
        if ($value === '') {
            $this->errors[$field] = "$label wajib diisi.";
        }
        return $this;
    }

    public function minLength(string $field, int $min, string $label): static {
        $value = trim($this->data[$field] ?? '');
        if ($value !== '' && mb_strlen($value) < $min) {
            $this->errors[$field] = "$label minimal $min karakter.";
        }
        return $this;
    }

    public function maxLength(string $field, int $max, string $label): static {
        $value = trim($this->data[$field] ?? '');
        if (mb_strlen($value) > $max) {
            $this->errors[$field] = "$label maksimal $max karakter.";
        }
        return $this;
    }

    public function email(string $field, string $label = 'Email'): static {
        $value = trim($this->data[$field] ?? '');
        if ($value !== '' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field] = "$label tidak valid.";
        }
        return $this;
    }

    public function password(string $field, string $label = 'Password'): static {
        $value = $this->data[$field] ?? '';
        if ($value !== '' && mb_strlen($value) < 8) {
            $this->errors[$field] = "$label minimal 8 karakter.";
        }
        return $this;
    }

    public function passwordConfirm(string $field, string $confirmField): static {
        if (($this->data[$field] ?? '') !== ($this->data[$confirmField] ?? '')) {
            $this->errors[$confirmField] = 'Konfirmasi password tidak cocok.';
        }
        return $this;
    }

    public function numeric(string $field, string $label): static {
        $value = $this->data[$field] ?? '';
        if ($value !== '' && !is_numeric($value)) {
            $this->errors[$field] = "$label harus berupa angka.";
        }
        return $this;
    }

    public function positiveInt(string $field, string $label): static {
        $value = $this->data[$field] ?? '';
        if ($value !== '' && (!(ctype_digit((string)$value)) || (int)$value <= 0)) {
            $this->errors[$field] = "$label harus berupa bilangan positif.";
        }
        return $this;
    }

    public function inList(string $field, array $allowed, string $label): static {
        $value = $this->data[$field] ?? '';
        if ($value !== '' && !in_array($value, $allowed, true)) {
            $this->errors[$field] = "$label tidak valid.";
        }
        return $this;
    }

    public function date(string $field, string $label, string $format = 'Y-m-d'): static {
        $value = $this->data[$field] ?? '';
        if ($value !== '') {
            $d = \DateTime::createFromFormat($format, $value);
            if (!$d || $d->format($format) !== $value) {
                $this->errors[$field] = "$label format tanggal tidak valid.";
            }
        }
        return $this;
    }

    public function url(string $field, string $label): static {
        $value = trim($this->data[$field] ?? '');
        if ($value !== '' && !filter_var($value, FILTER_VALIDATE_URL)) {
            $this->errors[$field] = "$label bukan URL yang valid.";
        }
        return $this;
    }

    public function phone(string $field, string $label = 'Nomor handphone'): static {
        $value = preg_replace('/\D/', '', $this->data[$field] ?? '');
        if ($value !== '' && (!preg_match('/^(08|628)\d{8,11}$/', $value))) {
            $this->errors[$field] = "$label tidak valid (contoh: 08123456789).";
        }
        return $this;
    }

    public function unique(string $field, string $table, string $column, string $label, int|string|null $excludeId = null): static {
        $value = trim($this->data[$field] ?? '');
        if ($value === '') return $this;

        try {
            $db = Database::getConnection();
            if ($excludeId !== null && $excludeId !== '') {
                $stmt = $db->prepare("SELECT COUNT(*) FROM `$table` WHERE `$column` = ? AND id != ?");
                $stmt->execute([$value, (int)$excludeId]);
            } else {
                $stmt = $db->prepare("SELECT COUNT(*) FROM `$table` WHERE `$column` = ?");
                $stmt->execute([$value]);
            }
            if ((int)$stmt->fetchColumn() > 0) {
                $this->errors[$field] = "$label sudah digunakan.";
            }
        } catch (\Exception $e) {
            logError('Validator::unique error', ['field' => $field, 'table' => $table]);
        }
        return $this;
    }

    public function custom(string $field, callable $callback, string $errorMessage): static {
        $value = $this->data[$field] ?? '';
        if (!$callback($value, $this->data)) {
            $this->errors[$field] = $errorMessage;
        }
        return $this;
    }

    public function passes(): bool {
        return empty($this->errors);
    }

    public function fails(): bool {
        return !empty($this->errors);
    }

    public function getErrors(): array {
        return $this->errors;
    }

    public function getFirstError(): string {
        return array_values($this->errors)[0] ?? '';
    }

    public function sanitized(array $fields = []): array {
        $sanitized = [];
        $keys = !empty($fields) ? $fields : array_keys($this->data);
        foreach ($keys as $key) {
            $value = $this->data[$key] ?? '';
            if (is_string($value)) {
                $sanitized[$key] = trim($value);
            } else {
                $sanitized[$key] = $value;
            }
        }
        return $sanitized;
    }

    public static function make(array $data): static {
        return new static($data);
    }
}
