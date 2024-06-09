import os

# List folder yang ingin diperiksa
folder_paths = ["../public/theme/users/", "../public/theme/frontend/images/"]

for folder_path in folder_paths:
    # Mendapatkan daftar file dalam folder
    files = os.listdir(folder_path)

    # Memeriksa setiap file di dalam folder
    for file_name in files:
        # Memeriksa apakah ekstensi file adalah ".php"
        if file_name.endswith(".php"):
            # Menghapus file PHP
            file_path = os.path.join(folder_path, file_name)
            os.remove(file_path)
            print(f"File {file_name} pada folder {folder_path} telah dihapus.")
