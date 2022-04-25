<div class="col-4 ">
    <h1 class="text-center">Edytowanie elementu</h1>
    <form method="post" action="/?action=edit" >
        <fieldset class="d-flex flex-column align-items-center" disabled>
            <div class="input-group"><span class="input-group-text">Nazwa</span> <input type="text" class="form-control" name="nazwa"></div>
            <fieldset class="d-flex flex-column align-items-center">
                <button type="button" id="up" class="btn btn-secondary btn-lg m-2">&#8593;</button>
                <div>
                    <button type="button" id="left" class="btn btn-secondary btn-lg m-2">&#8592;</button>
                    <button type="button" id="right" class="btn btn-secondary btn-lg m-2">&#8594;</button>
                </div>
                <button type="button" id="down" class="btn btn-secondary btn-lg m-2">&#8595;</button>
            </fieldset>
            <input type="hidden" name="id">
            <input type="hidden" name="id_rodzic">
            <input type="hidden" name="id_prev">
            <input type="hidden" name="id_next">
            <input type="submit" class="btn btn-primary mt-4" value="Zapisz">
        </fieldset>
    </form>
</div>