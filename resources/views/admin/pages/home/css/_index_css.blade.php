<style>
    .select2-container--default .select2-selection--single {
        height: 36px;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 36px;
    }

    .card {
        position: relative;
        overflow: hidden;
    }

    .overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        /* Warna hitam dengan transparansi */
        display: flex;
        justify-content: center;
        align-items: center;
        color: white;
        font-weight: bold;
        font-size: 1.5em;
        z-index: 1;
        pointer-events: none;
        /* Overlay tidak mengganggu interaksi klik pada card */
    }
</style>
