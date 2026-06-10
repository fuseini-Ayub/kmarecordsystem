<style>
.floating-help {
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 1000;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #1a56db;
    border-radius: 50%;
    box-shadow: 0 4px 12px rgba(26, 86, 219, 0.3);
    cursor: pointer;
    color: white;
    font-size: 20px;
    width: 48px;
    height: 48px;
    transition: background 0.15s ease, transform 0.15s ease;
}

.floating-help:hover {
    background-color: #1648c0;
    transform: scale(1.05);
}
</style>

<div class="floating-help" onclick="window.location.href='help.php'" title="Get help">
    <span>?</span>
</div>