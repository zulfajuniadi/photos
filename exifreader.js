var ExifImage = require('exif').ExifImage;

try {
  new ExifImage({
    image: process.argv[2]
  }, function(error, exifData) {
    if (!error)
      console.log(JSON.stringify(exifData)); // Do something with your data!
  });
} catch (error) {
  console.log(null);
}