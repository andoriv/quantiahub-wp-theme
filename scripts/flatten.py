from pathlib import Path
import shutil

ORIGEN = Path.cwd()
DESTINO = ORIGEN / "export"

CARPETAS_EXCLUIDAS = {"node", "node_modules"}
SEPARADOR = "_"  # Caracter que reemplaza los separadores de ruta

DESTINO.mkdir(parents=True, exist_ok=True)

total = 0
errores = 0

for archivo in ORIGEN.rglob("*"):
    if archivo == DESTINO or DESTINO in archivo.parents:
        continue

    ruta_relativa = archivo.relative_to(ORIGEN)

    if any(
        parte.lower() in CARPETAS_EXCLUIDAS
        for parte in ruta_relativa.parts
    ):
        continue

    if not archivo.is_file():
        continue

    # Nombre plano que codifica la ruta completa
    # Ejemplo: helpers/setup.php -> helpers_setup.php
    nombre_plano = SEPARADOR.join(ruta_relativa.parts)
    destino = DESTINO / nombre_plano

    try:
        shutil.copy2(archivo, destino)
        total += 1
        print(f"Copiado: {ruta_relativa} -> {nombre_plano}")
    except OSError as e:
        errores += 1
        print(f"Error: {archivo} -> {e}")

print("\nProceso finalizado.")
print(f"Archivos exportados: {total}")
print(f"Errores: {errores}")
print(f"Destino: {DESTINO}")