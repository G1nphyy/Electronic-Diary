# Electronic Diary

## Overview
The **Electronic Diary** is a journal adapted for one school. You can do all the things you need in an electronic school journal there. The all website is in Polish language because i'm from there ;)

## Features
- **Notes Management**: Create, edit, and delete notes.
- **Tests**: Keep track of important test.
- **Attendace**: Add attendance to your students.
- **User Authentication**: Secure login and registration for users.
- **Responsive Design**: Compatible with both desktop and mobile devices.

## Installation
To get started with the Electronic Diary, follow these steps:

1. **Clone the repository**:
    ```bash
    git clone https://github.com/G1nphyy/Electronic-Diary.git
    ```

2. **Navigate to the project directory**:
    - For Unix-like systems:
      ```bash
      cd Electronic-Diary/Mine
      ```
    - For Windows:
      ```cmd
      cd Electronic-Diary\Mine
      ```

3. **Move "Mine" to htdocs**:
    - For Unix-like systems:
      ```bash
      mv ../Mine /path/to/xampp/htdocs/
      ```
    - For Windows Command Prompt:
      ```cmd
      move ..\Mine C:\xampp\htdocs\
      ```
    - For Windows PowerShell:
      ```powershell
      Move-Item -Path ..\Mine -Destination C:\xampp\htdocs\
      ```

4. **Remember to create the database in phpMyAdmin**:
    ```sql
    CREATE DATABASE mine_db;
    ```

5. **Import tables**:
    - Use phpMyAdmin to import the `mine_db.sql` file into the newly created database `mine_db`.

6. **Start XAMPP Modules**:
    - Start Apache and MySQL modules in XAMPP.


## Usage
After installing and running the application, you can access it by navigating to [`http://localhost/mine/`](http://localhost/mine/) in your web browser. From there, you can create an account or log in to start using the diary features.

## Start accounts
  | Role | E-mail | Password |
  |----------|----------|----------|
  | Admin | adam@gmail.com | qwerty1234 |
  | Teacher | filip@gmail.com | qwerty1234 |
  | Student | lex@gmail.com | qwerty1234 |

## My plans

 - [ ] Add cancellation, postponement and replacement of lessons
 - [ ] Make it Multi school
 - [x] Drink more coffee


---

Thank you for using Electronic Diary!
