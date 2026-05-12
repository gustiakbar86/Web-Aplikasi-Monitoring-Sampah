<?php

namespace App\Traits;

use Illuminate\Support\MessageBag;
use Exception;

trait WithSweetAlert
{
    protected function successMessage($message)
    {
        return redirect()->back()->with('swal', [
            'icon' => 'success',
            'title' => 'Berhasil!',
            'text' => $message,
            'timer' => 3000,
            'showConfirmButton' => false
        ]);
    }

    protected function errorMessage($message, $errors = null)
    {
        $errorMessage = $message;
        
        if ($errors instanceof MessageBag) {
            $errorMessage .= "\n" . implode("\n", $errors->all());
        } elseif ($errors instanceof Exception) {
            $errorMessage .= "\n" . $errors->getMessage();
        }

        return redirect()->back()->with('swal', [
            'icon' => 'error',
            'title' => 'Gagal!',
            'text' => $errorMessage,
            'showConfirmButton' => true
        ])->withInput();
    }

    protected function warningMessage($message)
    {
        return redirect()->back()->with('swal', [
            'icon' => 'warning',
            'title' => 'Perhatian!',
            'text' => $message,
            'showConfirmButton' => true
        ]);
    }

    // Validation error handler
    protected function validationError($errors)
    {
        return $this->errorMessage('Validasi gagal:', $errors);
    }

    // Specific CRUD messages with redirect to route
    protected function createdSuccess($item = 'Data', $route = null)
    {
        $redirect = $route ? redirect()->route($route) : redirect()->back();
        return $redirect->with('swal', [
            'icon' => 'success',
            'title' => 'Berhasil!',
            'text' => "{$item} berhasil ditambahkan!",
            'timer' => 3000,
            'showConfirmButton' => false
        ]);
    }

    protected function updatedSuccess($item = 'Data', $route = null)
    {
        $redirect = $route ? redirect()->route($route) : redirect()->back();
        return $redirect->with('swal', [
            'icon' => 'success',
            'title' => 'Berhasil!',
            'text' => "{$item} berhasil diperbarui!",
            'timer' => 3000,
            'showConfirmButton' => false
        ]);
    }

    protected function deletedSuccess($item = 'Data')
    {
        return $this->successMessage("{$item} berhasil dihapus!");
    }

    protected function exportSuccess($item = 'Data')
    {
        return $this->successMessage("{$item} berhasil diexport!");
    }

    protected function createdError($item = 'Data', $error = '', $route = null)
    {
        $message = "{$item} gagal ditambahkan!";
        if ($error) {
            $message .= " Error: {$error}";
        }
        $redirect = $route ? redirect()->route($route) : redirect()->back();
        return $redirect->with('swal', [
            'icon' => 'error',
            'title' => 'Gagal!',
            'text' => $message,
            'showConfirmButton' => true
        ])->withInput();
    }

    protected function updatedError($item = 'Data', $error = '', $route = null)
    {
        $message = "{$item} gagal diperbarui!";
        if ($error) {
            $message .= " Error: {$error}";
        }
        $redirect = $route ? redirect()->route($route) : redirect()->back();
        return $redirect->with('swal', [
            'icon' => 'error',
            'title' => 'Gagal!',
            'text' => $message,
            'showConfirmButton' => true
        ])->withInput();
    }

    protected function deletedError($item = 'Data', $error = '')
    {
        $message = "{$item} gagal dihapus!";
        if ($error) {
            $message .= " Error: {$error}";
        }
        return $this->errorMessage($message);
    }
}