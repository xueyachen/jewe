<?php
return [	
		//应用ID,您的APPID。
		'app_id' => "2016092500595837",
		"seller_id" => "2088102177258313",

		//商户私钥
		'merchant_private_key' => "MIIEpAIBAAKCAQEAw9ut1B+j7oMMXVnqybBpyJ7UzcY1UL5cupq3It0qUoTR9eE6UKLf/2rgN5D5/+PICkQGD3WOsKSAjxTspWzN42WMhVOS3oPn3IlMer9ERb9Zc3pRvE0UglXOa6ejZBV90nhS3IppL8hLmfOz4AWwsyOdBgRh8I4jcDqQUFKl8aNTMsxfRS16Iezw12+fjbwWNl/urE0whts+nxczLkosqJDOL4vD1fk6bVRhVFNP93pc9ZcvSY1PaHLOxsYXK7RmEQQkkEoACtX+ZMya88G9c/E77G3+RkghptVNOoDqJ0U+16vSa8HlHM11PXFyCoQrGl+LGBAm7Y45SNASqG0F9wIDAQABAoIBAGncsF0qaPH/NUyz0DsJtnOwnfdHPgQRkI9wFrKqdji5/7516Y4yKv6kZuLB4s7T8IjT8a6jbOe/UpmDxtE3OSXC+qwJ2yWYiFdkgskJ3r4QSionoEw2IXK9LGB18NOk5ig/zxHTYj1odqyU09GvYa3B/xqEfeeG/FKp5Nz1fypWmg3XJDYPyPaAW/qAlhSxuW5+E2dRKB6cV8vui2KuiUsnsumnjq7POzwBMDLUAUnrVKHILyQ4cSuD/nNXu0i/aL/Q/3flnYN4J9gHya1zIAz1U6xEP1E2gNWwSBSnq9r34b1YHMdAkzXFPaRLufWYpJf3lhE9d0rHdXobXdg0QAkCgYEA4TLaNKz6AL2MoUDoLOtfZLup5hBtT5bDtu9nRkBY0h1TIpNKURVf+NpLeZ1gOy7wyceULfiZK92l5RiSf/bySFeHS3tICbPSX65x2i/A68p6ummVimNqtjyMgWtVmcaCBJOy5SgpNnaLhPQo6LrVT9wkGWZOv/rK2xW08xONfX0CgYEA3qV9/FVaFJEjN9OqOhL7HxTjEpuuvVFkYrfuQW62iKWVShDWWYjuHVY1tBesaHb0h5nyjMk8FIoTmYuy44MMydyKSbyFNwyhLfTKbQLI+uJDkrT6udFn6pfxfw9Alh7ehp+u772BUkeGByQmDgZGTYhkz5aKzHpT9J/WIMqiO4MCgYAGZRBHMKZEwR0uMw6kv+ZMkPupGcxQ10IlfrTbDNa42LZUpAQXO1knUuOTx4FFDPcvc6hkicunsDqWv9ThJ10H6txfq1eHVwDhUK3Q+UwsJerIQllJvbr5A80lm6upmzZz9NG+qh5JeqJJmzlSKoQGKYUo53vS/ccm433o7SQ2bQKBgQCqigrJeDsqm81lGq0+FIHj2eTgwP0EqE6DSy2lUv9gBa0ncWQuarMxJ3K1Qpjd35gIMgRWkGGDvgurRhU9LYljKZmHR26mrvbas7FF9KcrfJqTyipuoJa/W6kwUOeA1Z5PcgrWOcrP0lc4Kxk3DtlU1A+b7kzcuSqLqs6iiXPgrwKBgQCt4Xyfmvb25VNS3P3O5rRAaLVwi1xpxFvN68wARN1JQd54FaO1mD33ENDyPC7Y1Ds8WylbtQxLoVgAOh28cABcbfKWnzoAOe4V+NJbsEUc5k2URbRCurq3DhoVnXvAAJnyaIZSHT9wufFgQuyw0tizz9l94duNFoJtj7usg6/WXA==",
		
		//异步通知地址
		'notify_url' => "http://39.96.193.207/notify",
		
		//同步跳转
		'return_url' => "http://jewell.com/index/async",

		//编码格式
		'charset' => "UTF-8",

		//签名方式
		'sign_type'=>"RSA2",

		//支付宝网关
		'gatewayUrl' => "https://openapi.alipaydev.com/gateway.do",

		//支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
		'alipay_public_key' => "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAqBuMJJJSDtJFBMrmLi6c+65HdKXpJE0BHc4RZOh3DC33zNai3Edvl+L/lFIyMoQaf5aocBn982Lid2ewTypHUM4dHS7A1nwYEf/f0bglupi3CH1wLeV5nEt3c2IHj7KITHQSNDgP5H1/WMbz0ywOXWBBFyJqxaSsPr+VKue1E5Vs8M4z0VuxHdNzhlw8lpBguzED4nsZoPbvpBNTqVogrDiIhTkbr5tbuDh3QO1XOmuRr+kti/aQ6MXtdPJJEJSMQM1OLWuaosSnqwlluibJxZIT8PijzijZrgzRFEXBV9MG/aPz9mVD5FPpcXODsCfOuU6ZhMAzCLZHoTdpt+K90QIDAQAB",
]




?>