const uploadForm = document.getElementById("uploadForm");
const fileListElement = document.getElementById("fileList");
const uploadStatus = document.getElementById("uploadStatus");

window.onload = fetchFiles;

uploadForm.addEventListener("submit", async (event) => {
  event.preventDefault();

  const formData = new FormData();
  formData.append(
    "fileToUpload",
    document.getElementById("fileToUpload").files[0]
  );

  try {
    const response = await fetch("/api/upload", {
      method: "POST",
      body: formData,
    });

    const data = await response.json();

    if (data.status === 200) {
      uploadStatus.textContent = "File uploaded successfully!";
      uploadStatus.classList.remove("text-red-600");
      uploadStatus.classList.add("text-green-600");
    } else {
      uploadStatus.textContent = `Error: ${data.message}`;
    }
    location.reload();

    fetchFiles();
  } catch (error) {
    uploadStatus.textContent = "Error uploading file.";
    uploadStatus.classList.add("text-red-600");
  }
});

async function fetchFiles() {
  try {
    const response = await fetch("/api/files");
    const data = await response.json();

    fileListElement.innerHTML = "";
    Object.values(data).forEach((file) => {
      const listItem = document.createElement("li");
      listItem.classList.add(
        "flex",
        "justify-between",
        "items-center",
        "p-2",
        "border",
        "rounded-md"
      );
      listItem.innerHTML = `
                    <span>${file}</span>
                    <button class="bg-red-500 text-white px-2 py-1 rounded-md" onclick="deleteFile('${file}')">Delete</button>
                `;
      fileListElement.appendChild(listItem);
    });
  } catch (error) {
    console.error("Error fetching file list:", error);
  }
}

async function deleteFile(fileName) {
  if (!confirm(`Are you sure you want to delete the file "${fileName}"?`))
    return;

  try {
    const response = await fetch(`/api/delete?file=${fileName}`, {
      method: "DELETE",
    });
    console.log(response);
    const data = await response.json();

    if (data.status === 200) {
      alert("File deleted successfully");
      location.reload();
      fetchFiles();
    } else {
      alert("Error: " + data.message);
    }
  } catch (error) {
    console.error("Error deleting file:", error);
  }
}
