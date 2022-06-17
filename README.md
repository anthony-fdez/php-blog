# php-blog

php blog is a rest api for a simple blog where people can log in and read other people's blog posts and create their own

## How to run locally

To run this project locally you need to download XAMPP to be able to run the local MySQL database and run the code in a local server

After you have XAMPP installed import the database from dbTemplate.sql in the root of the project.

## Api Docs

There are protected api routes that the user needs to be logged in to access, for those you always need to pass a JSON Web Token generated after creating the user or logging in, and also the email of the user. This are passed in the header of the POST request

| Header        | Value                    |
| ------------- | ------------------------ |
| Authorization | Pass the JWT here        |
| Email         | Pass the user email here |

| Api                | Endpoint                          | Params                            | Send data as    |
| ------------------ | --------------------------------- | --------------------------------- | --------------- |
| Create new User    | "/blog/api/users/signup.php"      | "email", "password"               | JSON            |
| Login user         | "/blog/api/users/login.php"       | "email", "password"               | JSON            |
| Check if Logged In | "/blog/api/users/is_logged_in.php | Auth Header                       | Headers         |
| Sign out user      | "/blog/api/users/signout.php      | Auth Header                       | Headers         |
| Sign out all       | "/blog/api/users/signout_all.php  | Auth Header                       | Headers         |
| Delete             | "/blog/api/users/delete           | "email", "password", Auth Headers | JSON && Headers |
