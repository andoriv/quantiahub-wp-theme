# unflatten.py
from pathlib import Path
import shutil

ORIGEN = Path.cwd() / "export"
DESTINO = Path.cwd() / "restored"
SEPARADOR = "_"

DESTINO.mkdir(parents=True, exist_ok=True)

total = 0
errores = 0

for archivo in ORIGEN.iterdir():
    if not archivo.is_file():
        continue

    # helpers_setup.php -> Path("helpers", "setup.php") -> helpers/setup.php
    partes = archivo.name.split(SEPARADOR)
    ruta_restaurada = Path(*partes)
    destino = DESTINO / ruta_restaurada

    try:
        destino.parent.mkdir(parents=True, exist_ok=True)
        shutil.copy2(archivo, destino)
        total += 1
        print(f"Restaurado: {archivo.name} -> {ruta_restaurada}")
    except OSError as e:
        errores += 1
        print(f"Error: {archivo} -> {e}")

print("\nProceso finalizado.")
print(f"Archivos restaurados: {total}")
print(f"Errores: {errores}")
print(f"Destino: {DESTINO}")