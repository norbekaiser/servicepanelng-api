# Configuration Captcha
Norb Api supports the Following Captchas
* Friendlycaptcha
* RecpatchaV2
* RecpatchaV3

## captcha.ini
Copy captcha.ini.default to captcha.ini
```bash
cp captcha.ini.default captcha.ini
```

## Recaptcha V2
Default Values
```ini
[captcha]
Enabled = true
Type = 'recaptcha'

[recaptcha]
Version = 2
SecretKey = mysecretkey
```

## Recaptcha V3
Default Values
```ini
[captcha]
Enabled = true
Type = 'recaptcha'

[recaptcha]
Version = 3
SecretKey = mysecretkey
```

## Friendlycaptcha
Default Values
```ini
[captcha]
Enabled = true
Type = 'friendlycaptcha'

[friendlycaptcha]
SiteKey = mysitekey
SecretKey = mysecretkey
```
