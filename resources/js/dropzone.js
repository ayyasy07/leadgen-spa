import Dropzone from "dropzone";

(function () {
    "use strict";

    // Dropzone
    Dropzone.autoDiscover = false;
    $(".dropzone").each(function () {
        // SKIP kalau gak ada URL — biar gak crash di halaman yang gak pake upload
        let url = $(this).attr("action") || $(this).data("url");
        if (!url) return;

        let options = {
            url: url,
            accept: (file, done) => {
                console.log("Uploaded");
                done();
            },
        };

        if ($(this).data("single")) {
            options.maxFiles = 1;
        }

        if ($(this).data("file-types")) {
            options.accept = (file, done) => {
                if (
                    $(this).data("file-types").split("|").indexOf(file.type) ===
                    -1
                ) {
                    alert("Error! Files of this type are not accepted");
                    done("Error! Files of this type are not accepted");
                } else {
                    console.log("Uploaded");
                    done();
                }
            };
        }

        let dz = new Dropzone(this, options);

        dz.on("maxfilesexceeded", (file) => {
            alert("No more files please!");
        });
    });
})();