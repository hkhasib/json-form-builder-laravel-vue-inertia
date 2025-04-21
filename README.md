# 🧰 JSON Form Builder (Laravel + Vue + Inertia)

A powerful JSON-based dynamic form builder built with Laravel, Vue.js, Inertia.js, and MySQL. This application allows users to generate, preview, and edit forms visually using JSON data. Perfect for building customizable form-based tools.

> 🔗 Live at: [https://github.com/hkhasib/json-form-builder-laravel-vue-inertia](https://github.com/hkhasib/json-form-builder-laravel-vue-inertia)

---

## 🛠️ Setup Instructions

### 1. Clone the Repository

```bash
git clone https://github.com/hkhasib/json-form-builder-laravel-vue-inertia.git
cd json-form-builder-laravel-vue-inertia
```
### 2. Install Dependencies
```bash
composer install
npm install
```
### 3. Configure the Environment
- Copy .env.example to .env and update your MySQL credentials:
- If you face issues with Vite or Vue hot reload, add:
 `VITE_DEV_SERVER=true` to your `.env` file.
### 4. Run Migrations & Seeders
```bash
php artisan migrate --seed
```

### 5. Run the Application
```bash
php artisan serve
npm run dev
```

## 🚀 App Usage
### 1. Login
- Visit: http://127.0.0.1:8000 or http://127.0.0.1:8000/login

#### Admin Credentials:
```
Email: admin@admin.com

Password: password
```
#### User Credentials:
```
Email: user@admin.com
Password: password
```
### 2. Create or Upload JSON
- If you upload, the editor will be filled with json data automatically. Or write json manually.
- Make sure to follow the JSON structure:

```json
{
  "title": "Contact Form",
  "method": "POST",
  "action": "/submit",
  "fields": [
    {
      "type": "text",
      "name": "name",
      "label": "Name",
      "placeholder": "Enter your name",
      "required": true
    },
    {
      "type": "email",
      "name": "email",
      "label": "Email",
      "placeholder": "Enter your email",
      "required": true
    },
    {
      "type": "textarea",
      "name": "message",
      "label": "Message",
      "placeholder": "Enter your message",
      "required": true
    },
    {
      "type": "select",
      "name": "options",
      "label": "Choose an option",
      "required": true,
      "options": [
        { "value": "option1", "label": "Option 1" },
        { "value": "option2", "label": "Option 2" },
        { "value": "option3", "label": "Option 3" }
      ]
    }
  ]
}

```

### 3. Preview and Edit Form
- Click Next to preview the form.

- Click Continue to Editor to open the visual builder.

- Drag and drop to reorder fields.

- Click a field to update:

-- Label

-- Placeholder

-- CSS class name

-- Style
### 4. Save & Manage
- Change form status from Draft to Published.
- Click Update Entire Form to save changes.
- Navigate to All Forms.
- Click view to see the form.
- Click edit to edit the form.

## 🤝 Contributing
Pull requests are welcome! For major changes, please open an issue first to discuss what you would like to change.

## 📸 Screenshots
Here’s a preview of the app:

![Form Editor Screenshot](https://imgur.com/rfESeiY.png)
![JSON Editor Screenshot](https://imgur.com/URCACQJ.png)
![Form List Screenshot](https://imgur.com/atzcUoP.png)
![Form Preview Screenshot](https://imgur.com/NNDImz5.png)
