# StatusHub — Manual de instalación y uso

## ¿Qué es StatusHub?

StatusHub es una aplicación web para la monitorización de servicios IT en tiempo real.
Este manual explica cómo instalar y ejecutar el proyecto en cualquier ordenador.

---

## Requisitos previos

Solo necesitas instalar **una cosa**: Docker Desktop.

### Instalar Docker Desktop

1. Entra en https://www.docker.com/products/docker-desktop/
2. Descarga la versión para tu sistema operativo (Windows, Mac o Linux)
3. Ejecuta el instalador y sigue los pasos (siguiente, siguiente, finalizar)
4. Cuando termine, abre Docker Desktop y espera a que el icono de la ballena
   aparezca en la barra de tareas. Eso significa que Docker está listo.

> ⚠️ En Windows puede pedirte reiniciar el ordenador durante la instalación.

---

## Instalación del proyecto

### 1. Descarga el proyecto

Descarga el repositorio como archivo ZIP desde GitHub y descomprímelo,
o si tienes Git instalado ejecuta:
```bash
git clone https://gitlab.com/electroeyobs/statushub.git
```

### 2. Entra en la carpeta del proyecto

Abre una terminal (PowerShell en Windows, Terminal en Mac/Linux) y navega
hasta la carpeta donde descomprimiste el proyecto:
```bash
cd ruta/a/la/carpeta/statushub
```

### 3. Arranca el proyecto

Ejecuta este único comando:
```bash
docker-compose up -d
```

La primera vez tardará varios minutos porque descarga las imágenes necesarias.
Cuando termine verás algo como:
```
✔ Container statushub_db       Started
✔ Container statushub_app      Started
✔ Container statushub_swagger  Started
```

¡Ya está. El proyecto está funcionando!

---

## Uso

### Ver la documentación interactiva de la API

Abre el navegador y entra en:
```
http://localhost:8080
```

Verás la interfaz **Swagger UI** con todos los endpoints de la API documentados
y listos para probarse.

### Probar la API

#### Endpoints públicos (sin login)

Puedes probarlos directamente. Haz clic en cualquier endpoint,
luego en **"Try it out"** y después en **"Execute"**.

Algunos ejemplos:
- `GET /api/status` — Estado global del sistema
- `GET /api/services` — Lista de servicios
- `GET /api/incidents` — Lista de incidentes

#### Endpoints de administrador (requieren login)

1. Haz clic en `POST /api/auth/login` → "Try it out" → "Execute" con estas credenciales:
```json
   {
     "email": "admin@statushub.com",
     "password": "password"
   }
```
2. Copia el valor del campo `token` de la respuesta
3. Haz clic en el botón **Authorize 🔒** (arriba a la derecha)
4. Pega el token en el campo y haz clic en **Authorize**
5. A partir de ahí puedes usar todos los endpoints con candado 🔒

#### Credenciales disponibles

| Usuario | Email | Contraseña | Rol |
|---------|-------|------------|-----|
| Admin | admin@statushub.com | password | Administrador |
| Usuario | user@statushub.com | password | Usuario normal |

---

## Parar el proyecto

Cuando quieras apagar los contenedores ejecuta:
```bash
docker-compose down
```

Para volver a arrancarlo en el futuro, simplemente repite:
```bash
docker-compose up -d
```

---

## Solución de problemas

**El comando `docker-compose` no se reconoce**
Asegúrate de que Docker Desktop está instalado y en ejecución (icono de ballena en la barra de tareas).

**El puerto 8000 o 8080 está ocupado**
Cierra cualquier servidor local que tengas corriendo (XAMPP, etc.) antes de ejecutar el proyecto.

**La base de datos no carga datos**
Espera 30 segundos después de ejecutar `docker-compose up -d` y recarga el navegador.
MySQL necesita unos segundos para importar los datos iniciales.