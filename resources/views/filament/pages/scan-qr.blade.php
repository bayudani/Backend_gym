<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Scan QR Member</title>
    <!-- Instascan -->
    <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
</head>
<body>

    <h2>Scan QR Member</h2>

    <video id="preview" width="400" height="300"></video>

    <form method="POST" action="{{ route('absens.scan.store') }}" id="form">
        @csrf
        <input type="hidden" name="member_profile_id" id="member_id">
    </form>

    <script>
        let scanner = new Instascan.Scanner({
            video: document.getElementById('preview'),
            mirror: false
        });

        scanner.addListener('scan', function (content) {
            console.log("Scanned content: ", content);
            document.getElementById('member_id').value = content;
            document.getElementById('form').submit();
        });

        Instascan.Camera.getCameras().then(function (cameras) {
            if (cameras.length > 0) {
                scanner.start(cameras[0]);
            } else {
                alert('No cameras found');
            }
        }).catch(function (e) {
            console.error(e);
        });
    </script>

</body>
</html>
