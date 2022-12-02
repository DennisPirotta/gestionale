<?php

namespace App\Rules;

use App\Models\AccessKey;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Crypt;

class MatchAccessKey implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        $match = false;
        foreach (AccessKey::all() as $accessKey) {
            if ($value === Crypt::decryptString($accessKey->key)) {
                $match = true;
            }
        }

        return $match;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'The :attribute is incorrect.';
    }
}
