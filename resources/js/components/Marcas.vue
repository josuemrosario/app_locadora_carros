<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                
                <!-- Inicio Card de Busca-->
                
                <div class="card mb-3">
                    <div class="card-header">Busca de Marcas</div>

                    <div class="card-body">
                        <div class="form-row">
                            <div class="col mb-3">
                                
                                <!-- aula 351 -->
                                <input-container-component 
                                    titulo="ID" 
                                    id="InputId" 
                                    id-help="idHelp"
                                    texto-ajuda="Opcional. Informe o ID da marca">
                                
                                    <input type="number" class="form-control" id="inputId" aria-describedby="idHelp" placeholder="ID">
                                
                                </input-container-component>

                                
                                
                                
                            </div>
                            
                            <div class="col mb-3">

                                <!-- aula 351 -->
                                <input-container-component 
                                    titulo="Nome da Marca" 
                                    id="InputNome" 
                                    id-help="nomeHelp"
                                    texto-ajuda="Opcional. Informe o nome da marca">
                                
                                    <input type="text" class="form-control" id="inputNome" aria-describedby="nomeHelp" placeholder="Nome da Marca">
                                
                                </input-container-component>

    
                            </div>                            
                        </div>
                        


                    </div>
                    <div class="card-footer">               
                        <button type="submit" class="btn btn-primary btn-sm float-right">Pesquisar</button>
                    </div>
                </div>
                <!-- Fim Card de Busca-->

                <!-- Inicio Card de Listagem de Marcas-->
                <div class="card">
                    <div class="card-header">Relação de Marcas</div>

                    <div class="card-body">
 
                        <table-component></table-component>
                   
                    </div>
                    <div class="card-footer">               
                        <button type="button" class="btn btn-primary btn-sm float-right">Adicionar</button>
                    </div>
                </div>                
                <!-- Fim Card de Listagem de Marcas-->
            </div>
        </div>
    </div>
</template>

<script>

</script>
