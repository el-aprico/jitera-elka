<?php

namespace App\Http\Requests;

use App\Http\Requests\ApiFormRequest;
use Illuminate\Validation\Rule;

class UnfollowUserRequest extends ApiFormRequest
{
    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $request = [
            'user_id' => $this->route('userId'),
            'following_user_id' => $this->route('followingUserId')
        ];
        $this->merge($request);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_id' => [
                'required',
                Rule::exists('users', 'id')
            ],
            'following_user_id' => [
                'required',
                'different:user_id',
                Rule::exists('users', 'id'),
                Rule::exists('followings', 'following_user_id')
                    ->where('user_id', $this->user_id)
            ]
        ];
    }

    /**
     * Custom message for validation
     *
     * @return array
     */
    public function messages()
    {
        return [
            'user_id.required' => __('validation.follow_user.user_id.required'),
            'user_id.exists' => __('validation.follow_user.user_id.exists'),
            'following_user_id.required' => __('validation.follow_user.following_user_id.required'),
            'following_user_id.different' => __('validation.follow_user.following_user_id.different'),
            'following_user_id.exists' => __('validation.follow_user.following_user_id.exists'),
            'following_user_id.unique' => __('validation.follow_user.following_user_id.unique')
        ];
    }
}
