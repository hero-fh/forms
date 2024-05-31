Company Web Application
Overview This repository contains a web application developed for a private company. The application is designed to streamline internal processes through two main systems: an Incident Report System and an Online Recruitment System.

Features

Incident Report System The Incident Report System is a comprehensive module that allows employees to report and track incidents within the company. Key features include:
Incident Reporting: Employees can submit detailed reports of incidents, including descriptions, categories, and involved parties. Incident Tracking: View the status and history of reported incidents, ensuring they are resolved promptly. Notification System: Automatic notifications keep relevant parties informed about new incidents and updates. Data Analytics: Generate reports and analytics to identify trends and improve workplace safety. 2. Online Recruitment System The Online Recruitment System simplifies the hiring process, providing tools for both applicants and recruiters. Key features include:

Job Listings: Post and manage job openings with detailed descriptions and requirements. Application Portal: Candidates can apply online, upload resumes, and provide additional information. Application Tracking: Recruiters can track the progress of applications, assess the applicants, and manage communications with candidates. Automated Screening: Initial screening of applicants based on predefined criteria to streamline the selection process. Interview Management: Schedule and manage interviews, including automated feedback collection. Technologies Used Frontend: HTML, CSS, JavaScript, jQuery Backend: PHP Database: MySQL Installation and Setup Clone the repository:

bash Copy code git clone https://github.com/hero-fh/portfolio.git Navigate to the project directory:

bash Copy code cd yourrepository Set up your web server: Ensure you have a local server like XAMPP installed.

Copy the project to your server's root directory: Place the project files in the htdocs folder (or the equivalent directory for your server setup).

Create the database:

Open phpMyAdmin or your preferred MySQL interface. Create a new database. Import the provided SQL file (tspi_hr_db.sql) to set up the required tables. Configure the database connection:

Open the innitialize.php file in the root directory. Update the database connection details: php Copy code define('DB_SERVER', 'localhost'); define('DB_USERNAME', 'root'); define('DB_PASSWORD', ''); define('DB_DATABASE', 'tspi_hr_db'); Run the application: Open your web browser and navigate to http://localhost/e-recruitment/admin or http://localhost/forms/admin

Contribution Guidelines We welcome contributions from the community! Please follow these steps to contribute:

Fork the repository. Create a new branch (git checkout -b feature-branch). Make your changes. Commit your changes (git commit -m 'Add some feature'). Push to the branch (git push origin feature-branch). Create a new Pull Request. License This project is licensed under the MIT License. See the LICENSE file for more details.

Contact For any inquiries or issues, please contact [herohernando37@gmail.com].
