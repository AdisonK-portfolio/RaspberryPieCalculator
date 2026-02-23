This app's purpose is to show an API- a calculator made with the Laravel framework. 

Things to Note:

- **PHP Unit Testing**: see tests/Feature/CalculationTest.php
- **Vue Component**: see resources/js/pages/Calculator.vue
    - resources/js/components/Trash.vue is a helper component
- **Parsing**: see app/Models/Calculator.php
- **Resources**: see app/Http/Resourses/CalculationResource.php


Other features outside the calculator (other Vue components, account login/register feature, etc.) are default packages that came with the Laravel installation or package installations, etc.

To run, in the terminal, copy ./.env.example to ./.env, then run:
```
composer install
sail up -d
sail artisan key:generate
sail artisan migrate:fresh && sail artisan db:seed
sail npm install
sail npm run dev
```

To view the calculator page, login using:
- username: test@example.com
- password: password

then go to "[http://localhost/calculator](http://localhost/calculator)".

