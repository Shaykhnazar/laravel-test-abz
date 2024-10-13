import shell from 'shelljs';

if (shell.cp('../public/index.html', '../resources/views/index.blade.php').code !== 0) {
    console.error('Error: File copy failed');
    process.exit(1);
}
