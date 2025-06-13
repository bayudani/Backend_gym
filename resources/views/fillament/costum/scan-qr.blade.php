<x-filament-panels::page>
    <div>

        <div class="wrapper">
            <div class="scanner"></div>
            <video id="preview"></video>
        </div>

        <form id="scanForm" method="POST" action="{{ route('absens.scan.store') }}">
            @csrf
            <input type="hidden" name="member_profile_id" id="member_id">
        </form>
    </div>

    <script type="text/javascript" src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
    <script type="text/javascript">
        let scanner = new Instascan.Scanner({
            video: document.getElementById('preview')
        });
        scanner.addListener('scan', function(content) {
            console.log(content);
        });
        Instascan.Camera.getCameras().then(function(cameras) {
            if (cameras.length > 0) {
                scanner.start(cameras[0]);
            } else {
                console.error('No cameras found.');
            }
        }).catch(function(e) {
            console.error(e);
        });
        scanner.addListener('scan', function(c) {
            document.getElementById('member_id').value = c;
            document.getElementById('form').submit();
        })
    </script>

</x-filament-panels::page>
