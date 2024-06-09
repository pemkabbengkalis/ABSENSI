import os

def delete_php_files(folder_path):
    # Mendapatkan daftar file dalam folder
    files = os.listdir(folder_path)

    # Memeriksa setiap file di dalam folder
    for file_name in files:
        file_path = os.path.join(folder_path, file_name)
        # Memeriksa apakah file adalah folder
        if os.path.isdir(file_path):
            # Jika iya, panggil kembali fungsi delete_php_files untuk folder tersebut (rekursif)
            delete_php_files(file_path)
        # Jika file, memeriksa apakah ekstensi file adalah ".php"
        elif file_name.endswith(".php"):
            # Menghapus file PHP
            os.remove(file_path)
            print(f"File {file_name} pada folder {folder_path} telah dihapus.")

# Path awal yang ingin diperiksa
starting_folder = "../public/pegawai/"

# Panggil fungsi delete_php_files dengan path awal
delete_php_files(starting_folder)
