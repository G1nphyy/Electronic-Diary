# Electronic Diary

## Overview
The **Electronic Diary** is a journal adapted for one school. You can do all the things you need in an electronic school journal there. The all website is in Polish language because i'm from there ;)

## Images From Website

| ![login page](https://github.com/user-attachments/assets/038d7a19-9f47-4243-8ce6-c70a4e0e1a85) | ![News](https://github.com/user-attachments/assets/7742e31a-ddd2-4065-9318-50386e27d68d) | ![News](https://github.com/user-attachments/assets/c7378698-c8f8-4e4a-abf5-2d7352794f43) |
|:---:|:---:|:---:|
| ![obraz](https://github.com/user-attachments/assets/10f1665b-7891-4dd6-bf3f-5a4bfaef578a) | ![obraz](https://github.com/user-attachments/assets/a0b83e3d-1348-42e9-a950-0afef5f08b4f) | ![obraz](https://github.com/user-attachments/assets/9e3c2c4b-6a7a-4766-81df-6a972d426be2) |
| ![obraz](https://github.com/user-attachments/assets/c08139a4-ba83-4802-8b47-82d65fe75304) | ![obraz](https://github.com/user-attachments/assets/9c8b7731-a705-42c2-853f-d24ba58ad008) | ![obraz](https://github.com/user-attachments/assets/fa650103-846b-4d79-811d-287a8cf2e176) |
| ![obraz](https://github.com/user-attachments/assets/b05b2a84-f34a-4445-a607-64e1d7f60884) | ![obraz](https://github.com/user-attachments/assets/54fa47b1-b1ea-4b30-bea9-d180d59f25bc) | ![obraz](https://github.com/user-attachments/assets/afc8ff61-f313-48fe-8257-7daa6fba7744) |
| ![obraz](https://github.com/user-attachments/assets/b302a14a-fdfb-4e0c-a851-db4b8e571034) | ![obraz](https://github.com/user-attachments/assets/05ae8b07-50a5-4dfb-8b9e-d39f6a094599) | ![obraz](https://github.com/user-attachments/assets/bcb44e43-8c72-4c15-ae25-bc4c1f3e0449)|










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
      cd Electronic-Diary
      ```
    - For Windows:
      ```cmd
      cd Electronic-Diary
      ```

3. **Move "Mine" to htdocs**:
    - For Unix-like systems:
      ```bash
      mv Mine /path/to/xampp/htdocs/
      ```
    - For Windows Command Prompt:
      ```cmd
      move Mine C:\xampp\htdocs\
      ```
    - For Windows PowerShell:
      ```powershell
      Move-Item -Path Mine -Destination C:\xampp\htdocs\
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
