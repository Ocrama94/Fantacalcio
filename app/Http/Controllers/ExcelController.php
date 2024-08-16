<?php

namespace App\Http\Controllers;
// require_once 'vendor/autoload.php';

use App\Models\ActivePlayer;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Http\Request;
use App\Models\Player;

class ExcelController extends Controller
{   
    protected $DATA_FILE;
    protected $VOTES_FILE;
    protected $spreadsheet;
    protected $vote_spreadsheet;
    protected $WRITER;
    protected const ACTIVE_SHEET = 0;
    protected const ID_COL = 1;
    protected const VOTE_ID_COL = 1;
    protected const NAME_COL = 4;
    protected const VOTE_NAME_COL = 3;
    protected const ROLE_COL = 2;
    protected const VOTE_ROLE_COL = 2;
    protected const TEAM_COL = 5;
    protected const VOTE_COL = 4;

    public $teams = [];
    public $players = [];
    public $active_players = [];

    public function __construct(){
        $this->DATA_FILE =  IOFactory::load("../storage/app/docs/giocatori.xlsx");
        $this->VOTES_FILE =  IOFactory::load("../storage/app/docs/voti.xlsx");
        $this->spreadsheet = $this->DATA_FILE->getSheet($this::ACTIVE_SHEET);
        $this->vote_spreadsheet = $this->VOTES_FILE->getSheet($this::ACTIVE_SHEET);
        $this->spreadsheet->removeRow(1,2);
        $this->vote_spreadsheet->removeRow(1,4);

        // ricavo squadre
        for($i = 1; $this->spreadsheet->getCell([1,$i])->getValue(); $i++){
            $this->teams[] = $this->spreadsheet->getCell([$this::TEAM_COL,$i])->getValue();
        }
        $this->teams = array_unique($this->teams);

        // ricavo giocatori
        for($i = 1; $this->spreadsheet->getCell([1,$i])->getValue(); $i++){
            $this->players[] = new Player(
                // ID
                $this->spreadsheet->getCell([$this::ID_COL,$i])->getValue(),
                // Nome
                $this->spreadsheet->getCell([$this::NAME_COL,$i])->getValue(),
                // Ruolo
                $this->spreadsheet->getCell([$this::ROLE_COL,$i])->getValue(),
                // Squadra
                $this->spreadsheet->getCell([$this::TEAM_COL,$i])->getValue(),

            );

        }
        // ricavo giocatori attivi
        for($i = 1; $this->vote_spreadsheet->getCell([1,$i])->getValue(); $i++){
            if($this->vote_spreadsheet->getCell([$this::VOTE_NAME_COL,$i])->getValue() && $this->vote_spreadsheet->getCell([$this::VOTE_NAME_COL,$i])->getValue() !== 'Nome'){
                $this->active_players[] = new ActivePlayer(
                    // ID
                    $this->vote_spreadsheet->getCell([$this::VOTE_ID_COL,$i])->getValue(),
                    // Nome
                    $this->vote_spreadsheet->getCell([$this::VOTE_NAME_COL,$i])->getValue(),
                    // Ruolo
                    $this->vote_spreadsheet->getCell([$this::VOTE_ROLE_COL,$i])->getValue(),
                    // Voto
                    $this->vote_spreadsheet->getCell([$this::VOTE_COL,$i])->getValue(),
    
                );
            }
        }

    }

    public function welcome(){

        // dd($this->active_players);
        
        return view('welcome', [
            'players' => $this->players,
            'teams' => $this->teams,
            'active_players' => $this->active_players
        ]);

        
    }
}

