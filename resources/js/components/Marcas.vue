<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                
                <!-- Inicio Card de Busca-->

                <!-- aula 354 card transformado em vue -->
                <card-component titulo="Busca de Marcas">

                    <template v-slot:conteudo>

                        <div class="form-row">
                            <div class="col mb-3">
                                
                                <!-- aula 351 -->
                                <input-container-component 
                                    titulo="ID" 
                                    id="InputId" 
                                    id-help="idHelp"
                                    texto-ajuda="Opcional. Informe o ID da marca">
                                
                                    <input type="number" class="form-control" id="inputId" aria-describedby="idHelp" placeholder="ID" v-model="busca.id">
                                
                                </input-container-component>
                                
                            </div>
                            
                            <div class="col mb-3">

                                <!-- aula 351 -->
                                <input-container-component 
                                    titulo="Nome da Marca" 
                                    id="InputNome" 
                                    id-help="nomeHelp"
                                    texto-ajuda="Opcional. Informe o nome da marca">
                                
                                    <input type="text" class="form-control" id="inputNome" aria-describedby="nomeHelp" placeholder="Nome da Marca" v-model="busca.nome">
                                
                                </input-container-component>
                            
                            </div>                            
                        </div>

                    </template>

                    <template v-slot:rodape>
                        <button type="submit" class="btn btn-primary btn-sm float-right" @click="pesquisar()">Pesquisar</button>
                    </template>
                
                </card-component>
                

                <!-- Fim Card de Busca-->

                <!-- Inicio Card de Listagem de Marcas-->
                
                <!-- aula 354 card transofrmado em vue -->
                
                 <card-component titulo="Relação de Marcas">
                    
                    <template v-slot:conteudo>
                        
                        <table-component 
                            :dados="marcas.data"
                            :visualizar="{ visivel: true, dataToggle: 'modal', dataTarget: '#modalMarcaVisualizar' }"
                            :atualizar="{ visivel: true, dataToggle: 'modal', dataTarget: '#modalMarcaAtualizar' }"
                            :remover="{ visivel: true, dataToggle: 'modal', dataTarget: '#modalMarcaRemover' }"
                            :titulos="{
                                id: {titulo: 'ID', tipo: 'texto'},
                                nome: {titulo: 'NOME', tipo: 'texto'},
                                imagem: {titulo: 'Imagem', tipo: 'imagem'},
                                created_at: {titulo: 'Criação', tipo: 'data'}
                            }">
                        </table-component>


                    </template>

                    <template v-slot:rodape>
                        <div class="row">
                            <!-- aula 372 paginação-->
                            <div class="col-10">                                
                                <paginate-component>
                                    
                                    <li v-for="l, key in marcas.links" :key="key" 
                                        :class="l.active ? 'page-item active' : 'page-item' " 
                                        @click="paginacao(l)">
                                        <a class="page-link" v-html="l.label"></a>
                                    </li>
                                </paginate-component>
                            </div>
                            <div class="col">
                                <button type="button" class="btn btn-primary btn-sm float-right" data-toggle="modal" data-target="#modalMarca" >Adicionar</button>
                            </div>
                        </div>


                    </template>

                 </card-component>
                               
                <!-- Fim Card de Listagem de Marcas-->
            </div>
        </div>

        <!-- aula 356 -->
        <!-- Inicio modal inclusao de marca -->
        <modal-component id="modalMarca" titulo="Adicionar Marca" >
            
            
            <template v-slot:alertas>
                <alert-component tipo="success" :detalhes="transacaoDetalhes" titulo="Cadastro realizado com sucesso" v-if="transacaoStatus == 'adicionado'"></alert-component>
                <alert-component tipo="danger"  :detalhes="transacaoDetalhes" titulo="Erro ao tentar cadastrar a marca" v-if="transacaoStatus == 'erro'"></alert-component>
            </template>

            <!--conteudo do modal -->
            <template v-slot:conteudo >

               <div class="form-group">

                    <input-container-component 
                        titulo="Nome da Marca" 
                        id="InputNome" 
                        id-help="novoNomeHelp"
                        texto-ajuda="Opcional. Informe o nome da marca">
                    
                        <input type="text" class="form-control" id="novoNome" aria-describedby="novoNomeHelp" placeholder="Nome da Marca" v-model="nomeMarca">
                    
                    </input-container-component>
                    {{nomeMarca}}
                </div>    
                
                <div class="form-group">
                    <input-container-component 
                        titulo="Imagem" 
                        id="novoImagem" 
                        id-help="novoImagemHelp"
                        texto-ajuda="Selecione uma imagem no formato PNG">
                    
                        <input type="file" class="form-control-file" id="novoImagem" aria-describedby="novoImagemHelp" placeholder="Selecione uma imagem" @change="carregarImagem($event)">
                    
                    </input-container-component>
                    {{arquivoImagem}}
                </div>

            </template>

            <!-- Botoes que o modal devem mostrar -->
            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary" @click="salvar()">Salvar</button>
            </template>

        </modal-component>
        <!-- FIM modal inclusao de marca -->
        
        <!-- Inicio modal visualização de marca -->
        <modal-component id="modalMarcaVisualizar" titulo="Visualizar Marca" >
             <template v-slot:alertas>
             </template>
             <template v-slot:conteudo >
             
                <input-container-component titulo="ID">
                    <input type="text" class="form-control" :value="$store.state.item.id" disabled>
                </input-container-component>

                <input-container-component titulo="Nome da marca">
                    <input type="text" class="form-control" :value="$store.state.item.nome" disabled>
                </input-container-component>

                <input-container-component titulo="Imagem" v-if="$store.state.item.imagem">
                    <img :src="'storage/' + $store.state.item.imagem">
                </input-container-component>             

                <input-container-component titulo="Data Criação" >
                    <input type="text" class="form-control" :value="$store.state.item.created_at" disabled>
                </input-container-component>


             </template>
            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            </template>

        </modal-component>
        <!-- FIM modal visualização de marca -->

        <!-- Inicio modal remoção de marca -->
        <modal-component id="modalMarcaRemover" titulo="Remover Marca" >
             <template v-slot:alertas>
                <alert-component tipo="success"  titulo="Transação Realizada com Sucesso"  :detalhes="$store.state.transacao" v-if="$store.state.transacao.status=='sucesso'"> </alert-component>
                <alert-component tipo="danger" titulo="Erro na Transação" :detalhes="$store.state.transacao" v-if="$store.state.transacao.status=='erro'"> </alert-component>
             </template>
             <template v-slot:conteudo v-if="$store.state.transacao.status!='sucesso'">
             
                <input-container-component titulo="ID">
                    <input type="text" class="form-control" :value="$store.state.item.id" disabled>
                </input-container-component>

                <input-container-component titulo="Nome da marca">
                    <input type="text" class="form-control" :value="$store.state.item.nome" disabled>
                </input-container-component>           

             </template>
            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-danger" v-if="$store.state.transacao.status!='sucesso'" @click="remover()" >Remover</button>
            </template>

        </modal-component>
        <!-- FIM modal remoção de marca -->

        <!-- Inicio modal de atualização marca -->
        <modal-component id="modalMarcaAtualizar" titulo="Atualizar Marca" >
            
            
            <template v-slot:alertas>
                <!--  aula 387 -->
                <alert-component tipo="success"  titulo="Transação Realizada com Sucesso"  :detalhes="$store.state.transacao" v-if="$store.state.transacao.status=='sucesso'"> </alert-component>
                <alert-component tipo="danger" titulo="Erro na Transação" :detalhes="$store.state.transacao" v-if="$store.state.transacao.status=='erro'"> </alert-component>
            </template>

            <!--conteudo do modal -->
            <template v-slot:conteudo >

               <div class="form-group">

                    <input-container-component 
                        titulo="Nome da Marca" 
                        id="atualizarNome" 
                        id-help="atualizarNomeHelp"
                        texto-ajuda="Opcional. Informe o nome da marca">
                    
                        <input type="text" class="form-control" id="novoNome" aria-describedby="atualizarNomeHelp" placeholder="Nome da Marca" v-model="$store.state.item.nome">
                    
                    </input-container-component>

                </div>    
                
                <div class="form-group">
                    <input-container-component 
                        titulo="Imagem" 
                        id="atualizarImagem" 
                        id-help="atualizarImagemHelp"
                        texto-ajuda="Selecione uma imagem no formato PNG">
                    
                        <input type="file" class="form-control-file" id="atualizarImagem" aria-describedby="atualizarImagemHelp" placeholder="Selecione uma imagem" @change="carregarImagem($event)">
                    
                    </input-container-component>

                </div>

            </template>

            <!-- Botoes que o modal devem mostrar -->
            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary" @click="atualizar()">Atualizar</button>
            </template>

        </modal-component>
        <!-- FIM modal atualização  de marca -->

        
    </div>
</template>

<script>
    export default {
        //aula 360 cria o bearer token para ser passado para o header authorization
        //obs logica transferida para o bootstrap na aula 390

        data() {
            return {
                urlBase:'http://127.0.0.1:8000/api/v1/marca',
                urlPaginacao: '',
                urlFiltro: '',
                nomeMarca: '',
                arquivoImagem: [],
                transacaoStatus: '',
                transacaoDetalhes: {},
                marcas:{ data:[] },
                busca: { id:'', nome:''}
            }
        },
        methods: {
            atualizar(){
                console.log('nome atualizado',)
                console.log('imagem',)
                console.log('verbo http','patch')

                let formData = new FormData()
                formData.append('_method', 'patch')
                formData.append('nome', this.$store.state.item.nome)
                if(this.arquivoImagem[0]){
                    formData.append('imagem',this.arquivoImagem[0])
                }

                let config = {
                    headers: {
                        'Content-Type':'multipart/form-data',
                    }
                }

                let url = this.urlBase + '/' + this.$store.state.item.id

                axios.post(url,formData,config)
                    .then(response=>{

                            atualizarImagem.value = ''
                            this.$store.state.transacao.status = 'sucesso'
                            this.$store.state.transacao.mensagem = 'Registro de marca atualizado com sucesso'
                            this.carregarlista()

                        })
                        .catch(errors => {

                            this.$store.state.transacao.status = 'erro'
                            this.$store.state.transacao.mensagem = errors.response.data.message
                            this.$store.state.transacao.dados = errors.response.data.error
                            
                        })                



            },
            remover(){
                let confirmacao = confirm('tem certeza que deseja remover este registro ?')
                if(!confirmacao){
                    return false
                }
                
                let formData = new FormData()
                formData.append('_method', 'delete')





                let url = this.urlBase + '/' + this.$store.state.item.id
                
                axios.post(url,formData)
                    .then(response=>{
                        //console.log('registro removido com sucesso',response)
                        this.$store.state.transacao.status = 'sucesso'
                        this.$store.state.transacao.mensagem = response.data.msg
                        this.carregarlista()
                    })
                    .catch(errors => {
                        //console.log('Erro na tentativa de remoção do registro',errors.response.data)
                        this.$store.state.transacao.status = 'erro'
                        this.$store.state.transacao.mensagem = errors.response.data.msg

                    })

                

                
                
                //console.log('chegamos ate aqui')
            },
            pesquisar(){
                //console.log(this.busca)
                let filtro = ''

                for(let chave in this.busca){           
                    if(this.busca[chave]){
                        if(filtro != '')         {
                            filtro +=";"
                        }
                        filtro += chave + ':like:' + this.busca[chave]
                    }
                }
                
                if(filtro!=''){
                    this.urlPaginacao = 'page=1'
                    this.urlFiltro = '&filtro=' + filtro
                }else{
                    this.urlFiltro = ''
                }
                
                this.carregarlista()
            },
            paginacao(l){
                if(l.url){
                    //this.urlBase = l.url
                    
                    this.urlPaginacao = l.url.split('?')[1]
                    this.carregarlista()
                }  
            },
            carregarlista(){


                let url = this.urlBase + '?' + this.urlPaginacao + this.urlFiltro
                console.log(url)

                axios.get(url)
                    .then(response => {
                        this.marcas = response.data
                        //console.log(this.marcas)
                    })
                    .catch(errors => {
                        console.log(errors)
                    })
            },
            carregarImagem(e){
                this.arquivoImagem = e.target.files
            },
            salvar(){
                console.log(this.nomeMarca, this.arquivoImagem[0])

                let formData = new FormData()
                formData.append('nome', this.nomeMarca)
                formData.append('imagem', this.arquivoImagem[0])

                let config = {
                    headers: {
                        'Content-Type':'multipart/form-data',

                    }
                }

                axios.post(this.urlBase,formData,config)
                    .then(response => {
                        this.transacaoStatus = 'adicionado'
                        this.transacaoDetalhes = {
                            mensagem: 'ID do registro ' + response.data.id
                         
                        }
                        
                        //console.log(response)
                    })
                    .catch(errors => {
                        this.transacaoStatus = 'erro'
                        this.transacaoDetalhes = {
                            mensagem: errors.response.data.message,
                            dados: errors.response.data.errors
                        }
                        
                        
                        //console.log(errors.response.data.message)
                    })
            }
        },
        mounted(){
            this.carregarlista()
        }
    }

</script>
