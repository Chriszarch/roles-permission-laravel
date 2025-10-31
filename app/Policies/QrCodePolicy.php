<?php

namespace App\Policies;

use App\Models\QrCode;
use App\Models\User;

class QrCodePolicy
{
    public function view(User $authUser, QrCode $qrCode): bool
    {
        return $authUser->can('qr.view');
    }

    public function create(User $authUser): bool
    {
        return $authUser->can('qr.create');
    }

    public function update(User $authUser, QrCode $qrCode): bool
    {
        return $authUser->can('qr.edit');
    }

    public function delete(User $authUser, QrCode $qrCode): bool
    {
        return $authUser->can('qr.delete');
    }
}
