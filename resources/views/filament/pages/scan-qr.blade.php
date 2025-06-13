<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Scan QR Member</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Instascan -->
    <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="bg-white shadow-md rounded-lg p-6 w-full max-w-md">
        <h2 class="text-2xl font-bold text-center mb-4 text-gray-700">📷 Scan QR Member</h2>

        <div class="border-4 border-dashed border-gray-300 rounded-md overflow-hidden mb-4">
            <video id="preview" class="w-full h-auto rounded" autoplay muted></video>
        </div>

        <form method="POST" action="{{ route('absens.scan.store') }}" id="form">
            @csrf
            <input type="hidden" name="member_profile_id" id="member_id">
        </form>

        <div class="text-sm text-gray-500 text-center">Arahkan QR ke kamera untuk absen otomatis.</div>
    </div>

     <script>
    let scanner = new Instascan.Scanner({
        video: document.getElementById('preview'),
        mirror: false
    });

    let availableVoices = [];

    // Tunggu suara siap
    window.speechSynthesis.onvoiceschanged = () => {
        availableVoices = window.speechSynthesis.getVoices();
        console.log("Voice Loaded:", availableVoices);
    };

    function speak(text) {
        let msg = new SpeechSynthesisUtterance(text);
        let voice = availableVoices.find(v => v.lang === 'id-ID' && v.name.toLowerCase().includes('google'));

        if (voice) {
            msg.voice = voice;
        } else {
            console.warn("Voice ID ga ketemu, pake default.");
        }

        msg.pitch = 1;
        msg.rate = 1;
        msg.volume = 1;

        window.speechSynthesis.speak(msg);
    }

    scanner.addListener('scan', async function (content) {
        console.log("Scanned ID: ", content);
        document.getElementById('member_id').value = content;

        try {
            const response = await fetch(`/api/member-nama/${content}`);
            if (!response.ok) throw new Error("Member not found");

            const data = await response.json();
            const nama = data.nama;

            speak(`Selamat datang ${nama}`);

            setTimeout(() => {
                document.getElementById('form').submit();
            }, 2000);

        } catch (error) {
            alert("Gagal memuat data member!");
            console.error(error);
        }
    });

    Instascan.Camera.getCameras().then(cameras => {
        if (cameras.length > 0) {
            scanner.start(cameras[0]);
        } else {
            alert('Tidak ada kamera ditemukan.');
        }
    }).catch(e => console.error(e));
</script>


</body>
</html>
