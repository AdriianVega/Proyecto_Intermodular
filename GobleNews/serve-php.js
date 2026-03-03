import { spawn } from 'node:child_process';
import { existsSync } from 'node:fs';

const possiblePaths = [
    'C:/xampp/php/php.exe', 
    'php'                  
];

const phpBinary = possiblePaths.find(path => {
    if (path === 'php') return true;
    return existsSync(path);
});

if (!phpBinary) {
    console.error('Error: No se encontró PHP en las rutas definidas.');
    process.exit(1);
}

console.log(`\x1b[35m[PHP]\x1b[0m Usando binario en: ${phpBinary}`);

const phpServer = spawn(phpBinary, ['-S', 'localhost:8000'], {
    stdio: 'inherit',
    shell: true
});

phpServer.on('error', (err) => {
    console.error(`\x1b[31m[PHP] Error al iniciar el servidor: ${err.message}\x1b[0m`);
});