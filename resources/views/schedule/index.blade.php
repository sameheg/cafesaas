<h1>Schedule Board</h1>
<div id="schedule-board">
    @foreach($schedules as $schedule)
        <div class="schedule-item" draggable="true" data-id="{{ $schedule->id }}">
            {{ $schedule->title }} ({{ $schedule->starts_at->format('H:i') }} - {{ $schedule->ends_at->format('H:i') }})
        </div>
    @endforeach
</div>
<form>
    <label>Recurrence Rule</label>
    <input type="text" name="recurrence_rule" placeholder="FREQ=DAILY;COUNT=10">
</form>
<script>
document.querySelectorAll('.schedule-item').forEach(item => {
    item.addEventListener('dragstart', e => {
        e.dataTransfer.setData('text/plain', e.target.dataset.id);
    });
});
const board = document.getElementById('schedule-board');
board.addEventListener('dragover', e => e.preventDefault());
board.addEventListener('drop', e => {
    e.preventDefault();
    const id = e.dataTransfer.getData('text/plain');
    const el = document.querySelector('[data-id="'+id+'"]');
    board.appendChild(el);
});
</script>
