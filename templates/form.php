<div class="col-4">
    <h1>Edytowanie elementu</h1>
    <form method="post" action="/?action=edit">
        <label>Nazwa: <input type="text" name="nazwa"></label>
        <fieldset>
            <button type="button" id="up">&#8593;</button>
            <div>
                <button type="button" id="left">&#8592;</button>
                <button type="button" id="right">&#8594;</button>
            </div>
            <button type="button" id="down">&#8595;</button>
        </fieldset>
        <input type="hidden" name="id">
        <input type="hidden" name="id_rodzic">
        <input type="hidden" name="id_prev">
        <input type="submit" value="Zapisz">
    </form>
</div>