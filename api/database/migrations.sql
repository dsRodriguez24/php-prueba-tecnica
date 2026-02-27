-- Migraciones para pruebas
CREATE TABLE IF NOT EXISTS departamentos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL
);

CREATE TABLE IF NOT EXISTS municipios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  departamento_id INT NOT NULL,
  nombre VARCHAR(100) NOT NULL,
  FOREIGN KEY (departamento_id) REFERENCES departamentos(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS tipos_documento (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(50) NOT NULL
);

CREATE TABLE IF NOT EXISTS genero (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(50) NOT NULL
);

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(150) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role VARCHAR(50) DEFAULT 'user',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS paciente (
  id INT AUTO_INCREMENT PRIMARY KEY,
  tipo_documento_id INT,
  numero_documento VARCHAR(100),
  nombre1 VARCHAR(100),
  nombre2 VARCHAR(100),
  apellido1 VARCHAR(100),
  apellido2 VARCHAR(100),
  genero_id INT,
  departamento_id INT,
  municipio_id INT,
  correo VARCHAR(150),
  foto VARCHAR(255),
  activo INT(1) DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (tipo_documento_id) REFERENCES tipos_documento(id) ON DELETE SET NULL,
  FOREIGN KEY (genero_id) REFERENCES genero(id) ON DELETE SET NULL,
  FOREIGN KEY (departamento_id) REFERENCES departamentos(id) ON DELETE SET NULL,
  FOREIGN KEY (municipio_id) REFERENCES municipios(id) ON DELETE SET NULL
);
