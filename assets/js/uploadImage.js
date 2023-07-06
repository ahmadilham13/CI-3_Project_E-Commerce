// const imageUpload = document.getElementById('image')
const imageUpload = document.querySelectorAll('.images')[0]

let imagesArray = []
imageUpload.addEventListener('change', function() {
    const file = imageUpload.files
    if(file.length != 0) {
    imagesArray.push(file[0])
        displayImage()
    } else {
        displayImage()
    }
})

function displayImage() {
    let images = ""
    imagesArray.forEach((image, index) => {
        images = `<div class="image">
                <img src="${URL.createObjectURL(image)}" alt="image" height="150">
                <span onclick="deleteImage(${index})" style="cursor: pointer;">&times;</span>
              </div>`
    })
    const output = document.getElementById('images_display')
    output.innerHTML = images
}

function deleteImage(index) {
    imagesArray.splice(index, 1)
    imageUpload.value = ''
    displayImage()
  }