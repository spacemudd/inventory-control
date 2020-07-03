This is designed on Microsoft SQL (preferably), but you can use MySQL.

1. Copy `.env.example` to `.env`
2. (Windows) Download wkhtmltopdf.exe and have its path in .env.
3. Run migration `php artisan migrate --seed`
4. Setup your web server to use SSL with this website.
4. Use the default credentials to login: (`UsersTableSeeder`)

        username: 0
        password: secret
        
5. Use `npm install` and `npm run dev` or `npm run watch` to compile Vue components. 
