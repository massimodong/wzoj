<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'             => ':attribute 需要被勾选.',
    'active_url'           => ':attribute 不是一个合法的 URL.',
    'after'                => 'The :attribute must be a date after :date.',
    'alpha'                => ':attribute 只能包含字母.',
    'alpha_dash'           => ':attribute 只能包含字母、数字和下划线.',
    'alpha_num'            => ':attribute 只能包含字母和数字.',
    'array'                => ':attribute 应为一个数组.',
    'before'               => 'The :attribute must be a date before :date.',
    'between'              => [
        'numeric' => 'The :attribute must be between :min and :max.',
        'file'    => 'The :attribute must be between :min and :max kilobytes.',
        'string'  => 'The :attribute must be between :min and :max characters.',
        'array'   => 'The :attribute must have between :min and :max items.',
    ],
    'boolean'              => ':attribute 应为是或否.',
    'confirmed'            => ':attribute 不一致.',
    'captcha'              => '验证码错误.',
    'date'                 => ':attribute 不是一个合法的日期.',
    'date_format'          => ':attribute 不符合格式 :format.',
    'different'            => ':attribute 和 :other 应不同.',
    'digits'               => ':attribute 应包含 :digits 个字符.',
    'digits_between'       => ':attribute 应介于 :min 和 :max 字符之间.',
    'email'                => ':attribute 应为一个合法的邮箱地址.',
    'exists'               => '选择的 :attribute 不存在/不合法.',
    'filled'               => ':attribute 为必填项.',
    'image'                => ':attribute 应为一个图片.',
    'in'                   => '选择的 :attribute 不合法.',
    'integer'              => ':attribute 应为一个整数.',
    'ip'                   => ':attribute 应为一个合法的IP地址.',
    'json'                 => ':attribute 应为一个合法的JSON串.',
    'max'                  => [
        'numeric' => ':attribute 不能超过 :max.',
        'file'    => ':attribute 不能超过 :max KB.',
        'string'  => ':attribute 不能超过 :max 个字符.',
        'array'   => ':attribute 不能有超过 :max 个项目.',
    ],
    'mimes'                => ':attribute 应为以下文件类型: :values.',
    'min'                  => [
        'numeric' => ':attribute 不能小于 :min.',
        'file'    => ':attribute 不能小于 :min KB.',
        'string'  => ':attribute 不能小于 :min 个字符.',
        'array'   => ':attribute 应至少有 :min 个项目.',
    ],
    'not_in'               => '选择的 :attribute 不合法.',
    'numeric'              => ':attribute 应为一个数字.',
    'regex'                => ':attribute 的格式不正确.',
    'required'             => ':attribute 为必填项.',
    'required_if'          => 'The :attribute field is required when :other is :value.',
    'required_unless'      => 'The :attribute field is required unless :other is in :values.',
    'required_with'        => 'The :attribute field is required when :values is present.',
    'required_with_all'    => 'The :attribute field is required when :values is present.',
    'required_without'     => 'The :attribute field is required when :values is not present.',
    'required_without_all' => 'The :attribute field is required when none of :values are present.',
    'same'                 => ':attribute 和 :other 必须相同.',
    'size'                 => [
        'numeric' => 'The :attribute must be :size.',
        'file'    => 'The :attribute must be :size kilobytes.',
        'string'  => 'The :attribute must be :size characters.',
        'array'   => 'The :attribute must contain :size items.',
    ],
    'string'               => ':attribute 应为一个字符串.',
    'timezone'             => 'The :attribute must be a valid zone.',
    'unique'               => ':attribute 已经被使用了.',
    'url'                  => ':attribute 格式不合法.',
    'username' => '用户名只能包含大小写英文字母、数字和下划线，且以英文字母或下划线开头，长度大于等于3，小于等于31.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [
	    'problemset_id' => '题库编号',
	    'problem_id' => '题目编号',
	    'score_min' => '最低分',
	    'score_max' => '最高分',
	    'language' => '语言',
	    'password' => '密码',
	    'old_password' => '旧密码',
	    'new_password' => '新密码',
	    'name' => '用户名',
	    'email' => '邮箱',
	    'fullname' => '姓名',
	    'class' => '班级',
    ],

];
