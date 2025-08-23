# Recruiter-AI

פלטפורמת ראיונות מבוססת AI לגיוס עובדים.

## התקנה והרצה

### דרישות קדם

*   PHP 8.2
*   Composer 2.x
*   Node.js 20.x (עם npm או yarn)
*   MySQL 8.x

### התקנה

1.  שכפל את המאגר:
    ```bash
    git clone https://github.com/your-repo/recruiter-ai.git
    cd recruiter-ai
    ```

2.  התקן תלויות PHP:
    ```bash
    composer install
    ```

3.  התקן תלויות Node.js:
    ```bash
    npm install
    ```

4.  העתק את קובץ הגדרות הסביבה והגדר אותו:
    ```bash
    cp config/env.example .env
    ```
    יש לערוך את קובץ ה-`.env` ולהגדיר את פרטי ההתחברות למסד הנתונים, מפתחות API וכו'.

### הרצת שרת הפיתוח

שרת ה-PHP המובנה:
```bash
php -S 0.0.0.0:8080 -t public
```

הרצת שירותי ה-frontend (בטרמינל נפרד):
```bash
npm run dev --workspace=app
npm run dev --workspace=admin
```

האפליקציה תהיה זמינה בכתובת `http://localhost:8080`.

## הרצת בדיקות

כדי להריץ את חבילת הבדיקות האוטומטיות (PHPUnit), יש להריץ את הפקודה הבאה מהספרייה הראשית של הפרויקט:

```bash
./vendor/bin/phpunit
```

הבדיקות משתמשות במסד נתונים מסוג SQLite in-memory והן רצות באופן מבודד מההגדרות המקומיות שלך.
