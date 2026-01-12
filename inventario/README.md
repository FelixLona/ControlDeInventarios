# 📆 Sistema de Inventario TSJZ - Laravel 

Sistema web para gestión de inventarios, con trazabilidad, responsables, exportación a Excel y panel administrativo moderno.

---

## 🚀 Funcionalidades principales

* Registro y login de usuarios.
* Dashboard con todos los productos del inventario.
* CRUD de productos, categorías, inventario, ubicaciones y responsables.
* Trazabilidad e historial de movimientos.
* Asignación de responsables y documentos de compromiso.
* Exportación de productos a Excel.
* Panel de administración con Filament.

---

## ⚙️ Requisitos del sistema

| Requisito     | Versión recomendada    |
| ------------- | ---------------------- |
| PHP           | 8.1 o superior         |
| Composer      | 2.x                    |
| MySQL/MariaDB | 5.7 / 10.x o superior  |
| Servidor Web  | Apache / Nginx         |
| Node.js       | Opcional (para assets) |

---

## 📅 Instalación local paso a paso

1. **Clonar el repositorio**

```bash
git clone https://github.com/Uaybalam/inventario.git
cd inventario
```

2. **Instalar dependencias**

```bash
composer install
```

3. **Copiar y configurar .env**

```bash
cp .env.example .env
```

Edita `.env` con tus datos de base de datos.

4. **Generar clave de la aplicación**

```bash
php artisan key:generate
```

5. **Migrar base de datos y poblar datos iniciales**

```bash
php artisan migrate:fresh --seed
```

6. **Ejecutar el servidor**

```bash
php artisan serve
```

Accede en el navegador: [http://localhost:8000/admin](http://localhost:8000/admin)

---

## 📄 Credenciales de acceso por defecto

| Usuario                                       | Contraseña |
| --------------------------------------------- | ---------- |
| [admin@example.com](mailto:admin@example.com) | password   |

---

## 📥 Exportar base de datos a archivo SQL

```bash
mysqldump -u usuario -p inventario > inventario_backup.sql
```

---

# 🚀 Despliegue en servidor Linux (Ubuntu)

## 1. Subir archivos al servidor

```bash
scp -r ./inventario usuario@ip-servidor:/var/www/inventario
```

## 2. Conectarse y entrar a la carpeta

```bash
ssh usuario@ip-servidor
cd /var/www/inventario
```

## 3. Instalar dependencias PHP

```bash
composer install --no-dev --optimize-autoloader
```

## 4. Configurar entorno .env

```bash
cp .env.example .env
nano .env
```

## 5. Generar clave

```bash
php artisan key:generate
```

## 6. Migrar base de datos

```bash
php artisan migrate --force
```

---

# 🔌 Configurar Apache

1. Crear archivo:

```bash
sudo nano /etc/apache2/sites-available/inventario.conf
```

2. Contenido ejemplo:

```apacheconf
<VirtualHost *:80>
    ServerName midominio.com
    DocumentRoot /var/www/inventario/public

    <Directory /var/www/inventario>
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/inventario_error.log
    CustomLog ${APACHE_LOG_DIR}/inventario_access.log combined
</VirtualHost>
```

3. Activar sitio:

```bash
sudo a2ensite inventario.conf
sudo a2enmod rewrite
sudo systemctl restart apache2
```

---

## 📂 Permisos correctos

```bash
sudo chown -R www-data:www-data /var/www/inventario
sudo chmod -R 775 storage bootstrap/cache
```

---

## 🔐 Optimizar para producción

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---
