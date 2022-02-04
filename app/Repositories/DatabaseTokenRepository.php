<?php

namespace App\Repositories;

use Illuminate\Auth\Passwords\DatabaseTokenRepository as BaseDatabaseTokenRepository;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class DatabaseTokenRepository extends BaseDatabaseTokenRepository
{
    /**
     * Create a new token record.
     *
     * @param  \Illuminate\Contracts\Auth\CanResetPassword  $user
     * @return string
     */
    public function create(CanResetPasswordContract $user)
    {
        $email = $user->getEmailForPasswordReset();

        $this->deleteExisting($user);

        // We will create a new, random token for the user so that we can e-mail them
        // a safe link to the password reset form. Then we will insert a record in
        // the database so that we can verify the token within the actual reset.
        $token = $this->createNewToken();

        $code = strtoupper(Str::random(6));

        $this->getTable()->insert($this->getPayload($email, $token, $code));

        return request()->expectsJson() ? $code : $token;
    }

    /**
     * Build the record payload for the table.
     *
     * @param  string  $email
     * @param  string  $token
     * @param  string  $code
     * @return array
     */
    protected function getPayload($email, $token, $code = null)
    {
        return [
            'email' => $email,
            'token' => $this->hasher->make($token),
            'code' => $code,
            'created_at' => new Carbon
        ];
    }

    /**
     * Determine if a token record exists and is valid.
     *
     * @param  \Illuminate\Contracts\Auth\CanResetPassword  $user
     * @param  string  $token
     * @return bool
     */
    public function exists(CanResetPasswordContract $user, $token)
    {
        $record = (array) $this->getTable()->where(
            'email', $user->getEmailForPasswordReset()
        )->first();

        return $record &&
               ! $this->tokenExpired($record['created_at']) &&
                 $this->validate($token, $record);
    }

    protected function validate($token, $record)
    {
        if ($this->hasher->check($token, $record['token']) || $token === $record['code']) {
            return true;
        }

        return false;
    }
}
