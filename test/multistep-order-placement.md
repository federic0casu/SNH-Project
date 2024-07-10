Test
====

Testing payment method processing
---------------------------------

**(1)** No browser, making a POST request to `php/process_payment_method.php` without providing required field 'firstname':
```sh
$ curl -i -X POST \
  https://localhost/php/process_payment_method.php \
  -H 'Content-Type: application/x-www-form-urlencoded' \
  -d 'lastname=Doe' \
  -d 'address=123 Main St' \
  -d 'city=Anytown' \
  -d 'postal_code=12345' \
  -d 'country=USA' \
  -d 'card_number=4111111111111111' \
  -d 'expiry_date=12/24' \
  -d 'cvv=123' \
  --cookie "user_login=630c947ce5a6d9652108a1a2455ebbf40fe935ea0a5dd78f50050d7d2bb60ebf; HttpOnly"

HTTP/1.1 302 Found
Date: Wed, 10 Jul 2024 15:25:09 GMT
Server: Apache/2.4.59 (Debian)
X-Powered-By: PHP/8.3.9
Set-Cookie: PHPSESSID=d5f5c73baec98effd82c161597ae3378; path=/
Expires: Thu, 19 Nov 1981 08:52:00 GMT
Cache-Control: no-store, no-cache, must-revalidate
Pragma: no-cache
Location: //localhost/pages/error.php?error=To+proceed+with+your+order%2C+please+enter+your+first+name+before+continuing+to+checkout.&link=checkout.php
Content-Length: 0
Content-Type: text/html; charset=UTF-8
```

**(2)** No browser, making a POST request to `php/process_payment_method.php` without providing required field 'lastname':
```sh
$ curl -i -X POST \
  https://localhost/php/process_payment_method.php \
  -H 'Content-Type: application/x-www-form-urlencoded' \
  -d 'firstname=John' \
  -d 'address=123 Main St' \
  -d 'city=Anytown' \
  -d 'postal_code=12345' \
  -d 'country=USA' \
  -d 'card_number=4111111111111111' \
  -d 'expiry_date=12/24' \
  -d 'cvv=123' \
  --cookie "user_login=630c947ce5a6d9652108a1a2455ebbf40fe935ea0a5dd78f50050d7d2bb60ebf; HttpOnly"

HTTP/1.1 302 Found
Date: Wed, 10 Jul 2024 15:27:55 GMT
Server: Apache/2.4.59 (Debian)
X-Powered-By: PHP/8.3.9
Set-Cookie: PHPSESSID=60f058b86ad6124d1d799985a0c62dea; path=/
Expires: Thu, 19 Nov 1981 08:52:00 GMT
Cache-Control: no-store, no-cache, must-revalidate
Pragma: no-cache
Location: //localhost/pages/error.php?error=To+proceed+with+your+order%2C+please+enter+your+last+name+before+continuing+to+checkout.&link=checkout.php
Content-Length: 0
Content-Type: text/html; charset=UTF-8
```

**(3)** No browser, making a POST request to `php/process_payment_method.php` without providing required field 'address':
```sh
$ curl -i -X POST \
  https://localhost/php/process_payment_method.php \
  -H 'Content-Type: application/x-www-form-urlencoded' \
  -d 'firstname=John' \
  -d 'lastname=Doe' \
  -d 'city=Anytown' \
  -d 'postal_code=12345' \
  -d 'country=USA' \
  -d 'card_number=4111111111111111' \
  -d 'expiry_date=12/24' \
  -d 'cvv=123' \
  --cookie "user_login=630c947ce5a6d9652108a1a2455ebbf40fe935ea0a5dd78f50050d7d2bb60ebf; HttpOnly"

HTTP/1.1 302 Found
Date: Wed, 10 Jul 2024 15:29:16 GMT
Server: Apache/2.4.59 (Debian)
X-Powered-By: PHP/8.3.9
Set-Cookie: PHPSESSID=5bccb5f3c52ce2b8c074c4c0bb31f18f; path=/
Expires: Thu, 19 Nov 1981 08:52:00 GMT
Cache-Control: no-store, no-cache, must-revalidate
Pragma: no-cache
Location: //localhost/pages/error.php?error=To+proceed+with+your+order%2C+please+enter+your+billing+address+before+continuing+to+checkout.&link=checkout.php
Content-Length: 0
Content-Type: text/html; charset=UTF-8
```

**(4)** No browser, making a POST request to `php/process_payment_method.php` without providing required field 'cvv':
```sh
$ curl -i -X POST \
  https://localhost/php/process_payment_method.php \
  -H 'Content-Type: application/x-www-form-urlencoded' \
  -d 'firstname=John' \
  -d 'lastname=Doe' \
  -d 'address=123 Main St' \
  -d 'city=Anytown' \
  -d 'postal_code=12345' \
  -d 'country=USA' \
  -d 'card_number=4111111111111111' \
  -d 'expiry_date=12/24' \
  --cookie "user_login=630c947ce5a6d9652108a1a2455ebbf40fe935ea0a5dd78f50050d7d2bb60ebf; HttpOnly"

HTTP/1.1 302 Found
Date: Wed, 10 Jul 2024 15:33:34 GMT
Server: Apache/2.4.59 (Debian)
X-Powered-By: PHP/8.3.9
Set-Cookie: PHPSESSID=9f4e73b75afaa8460fa7a68d1d4fa8f6; path=/
Expires: Thu, 19 Nov 1981 08:52:00 GMT
Cache-Control: no-store, no-cache, must-revalidate
Pragma: no-cache
Location: //localhost/pages/error.php?error=To+proceed+with+your+order%2C+please+enter+your+card+CVV+before+continuing+to+checkout.&link=checkout.php
Content-Length: 0
Content-Type: text/html; charset=UTF-8
```

Testing shipping address processing
-----------------------------------

**(1)** No browser, making a POST request to `php/process_shipping.php` without providing required field 'address':
```sh
curl -i -X POST \
  https://localhost/php/process_shipping.php \
  -H 'Content-Type: application/x-www-form-urlencoded' \
  -d 'city=Anytown' \
  -d 'postal_code=12345' \
  -d 'country=USA' \
  --cookie "user_login=630c947ce5a6d9652108a1a2455ebbf40fe935ea0a5dd78f50050d7d2bb60ebf; HttpOnly" \
  --cookie "PHPSESSID=0df2c61906c0ad1c0c33ab6a5a35675e; HttpOnly

HTTP/1.1 302 Found
Date: Wed, 10 Jul 2024 15:56:28 GMT
Server: Apache/2.4.59 (Debian)
X-Powered-By: PHP/8.3.9
Expires: Thu, 19 Nov 1981 08:52:00 GMT
Cache-Control: no-store, no-cache, must-revalidate
Pragma: no-cache
Location: //localhost/pages/error.php?error=To+proceed+with+your+order%2C+please+enter+your+shipping+address+before+continuing+to+checkout.&link=shipping_address.php
Content-Length: 0
Content-Type: text/html; charset=UTF-8
```