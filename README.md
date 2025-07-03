### 1. Clone the Repository

Open a terminal and run the following command:

```bash
git clone https://github.com/Tan051107/Facilities-Management-System.git
```

### 2. Navigate to the Project Directory

```bash
cd Facilities-Management-System
```

---


### 3. Database Setup

1. **Create Database**

   Open your MySQL client or phpMyAdmin and create a new database called `unisphere`:

   ```sql
   CREATE DATABASE unisphere;
   ```

2. **Import SQL File**

   Import the provided `unisphere.sql` file into the `unisphere` database.

   - In **phpMyAdmin**:
     - Select the `unisphere` database.
     - Go to the **Import** tab.
     - Upload the `unisphere.sql` file.
     - Click **Go** to import.

3. **Create Database Connection**

   Create a file named `connection.php` in your project root and add the following code:

   ```php
   <?php
   $servername = "localhost";
   $username = "your_username"; // Replace with your MySQL username
   $password = "your_password"; // Replace with your MySQL password
   $database = "unisphere";    // Database name

   $conn = new mysqli($servername, $username, $password, $database);

   if ($conn->connect_error) {
     die("Connection failed: " . $conn->connect_error);
   }
   ?>
   ```

   Make sure to replace `your_username` and `your_password` with your actual database credentials.

---










