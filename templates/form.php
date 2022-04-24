<div class="col-4">
    <h1>Edytowanie elementu</h1>
    <form method="post" action="/?action=edit">
        <label>Nazwa: <input type="text" name="nazwa"></label>
        <fieldset>
            <button id="up">&#8593;</button>
            <div>
                <button id="left">&#8592;</button>
                <button id="right">&#8594;</button>
            </div>
            <button id="down">&#8595;</button>
        </fieldset>
        <input type="hidden" name="id" value="">
        <input type="hidden" name="id_rodzic" value="">
        <input type="hidden" name="id_prev" value="">
        <input type="submit" value="Zapisz">
    </form>
</div>