<?php
/**
 * Created by PhpStorm.
 * User: andrestntx
 * Date: 9/20/16
 * Time: 3:27 PM
 */

namespace App\Http\Controllers\Admin;


use App\Entities\Platform\Space\Space;
use App\Entities\Proposal\Proposal;
use App\Facades\AdvertiserFacade;
use App\Facades\DatatableFacade;
use App\Facades\ProposalFacade;
use App\Facades\SpaceFacade;
use App\Http\Controllers\Controller;
use App\Http\Requests\Proposal\AddSpaceRequest;
use Illuminate\Http\Request;

class ProposalsController extends Controller
{

    protected $advertiserFacade;
    protected $proposalFacade;
    protected $spaceFacade;
    protected $datatableFacade;


    public function __construct(AdvertiserFacade $advertiserFacade, ProposalFacade $proposalFacade, SpaceFacade $spaceFacade,
            DatatableFacade $datatableFacade)
    {
        $this->advertiserFacade = $advertiserFacade;
        $this->proposalFacade = $proposalFacade;
        $this->spaceFacade = $spaceFacade;
        $this->datatableFacade = $datatableFacade;
    }

    /**
     * @param Request $request
     * @return string
     */
    public function index(Request $request)
    {
        return view('admin.proposals.lists');
    }

    /**
     * @param Request $request
     * @param Proposal $proposal
     * @return string
     */
    public function update(Request $request, Proposal $proposal)
    {
        $proposal->fill($request->all());
        $proposal->save();

        return ['success' => 'true', 'proposal' => $proposal];
    }

    /**
     * @param Request $request
     * @param Proposal $proposal
     * @return array
     */
    public function delete(Request $request, Proposal $proposal)
    {
        $this->proposalFacade->delete($proposal);
        return ['success' => 'true'];
    }

    /**
     * @param Proposal $proposal
     * @return $this
     */
    public function show(Proposal $proposal)
    {
        $proposal->load(['viewSpaces.audiences.type', 'viewSpaces.cities', 'viewSpaces.impactScenes',
            'quote.questions', 'spaceAudiences', 'cities', 'impactScenes', 'contacts.actions']);
        $finalProposal = clone $proposal;

        $finalProposal->viewSpaces = $finalProposal->viewSpaces->filter(function($space, $key) {
            return $space->pivot->selected;
        });

        $advertiser = $proposal->quote->viewAdvertiser()->with([
            'contacts.actions', 'intentions', 'views', 'logs', 'favorites'
        ])->get()->first();

        $advertiser->setAppendsForProposal();

        $contacts = $proposal->contacts->sortByDesc('created_at')->all();

        return view('admin.proposals.show')->with([
            'proposal'      => $proposal,
            'finalProposal' => $finalProposal,
            'advertiser'    => $advertiser,
            'contacts'      => $contacts
        ]);
    }

    /**
     * @param Request $request
     * @param Proposal $proposal
     * @return $this
     */
    public function previewPdf(Request $request, Proposal $proposal)
    {
        $proposal = $this->proposalFacade->getSelected($proposal);

        return \PDF::loadView('admin.proposals.preview.pdf', [
            'proposal'      => $proposal,
            'advertiser'    => $proposal->getViewAdvertiser()
        ])->setPaper('a4')->stream('cotizacion_dondepauto.pdf');
    }

    /**
     * @param Request $request
     * @param Proposal $proposal
     * @return $this
     */
    public function previewAllPdf(Request $request, Proposal $proposal)
    {
        $proposal = $this->proposalFacade->getProposal($proposal);

        return \PDF::loadView('admin.proposals.preview.pdf', [
            'proposal'      => $proposal,
            'advertiser'    => $proposal->getViewAdvertiser()
        ])->setPaper('a4')->stream('cotizacion_dondepauto.pdf');
    }

    /**
     * @param Request $request
     * @param Proposal $proposal
     * @return $this
     */
    public function select(Request $request, Proposal $proposal)
    {
        $proposal = $this->proposalFacade->select($proposal, $request->get("spaces"));
        return ['success' => 'true', 'file' => route('proposals.preview-pdf', $proposal)];
    }

    /**
     * @param Proposal $proposal
     * @return $this
     */
    public function previewHtml(Proposal $proposal)
    {
        $proposal->load(['quote.advertiser', 'viewSpaces.images']);

        return view('admin.proposals.preview.html')->with([
            'proposal'   => $proposal,
            'advertiser' => $proposal->getViewAdvertiser()
        ]);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function search(Request $request)
    {
        return $this->datatableFacade->searchProposals($request->get('columns'), $request->get('search')['value'], $request->all());
    }

    /**
     * @param Request $request
     * @param Proposal $proposal
     * @return mixed
     */
    public function searchSpaces(Request $request, Proposal $proposal)
    {
        return $this->datatableFacade->searchSpaces($request->get('columns'), $request->get('search')['value'], null, null, $proposal, $request->all());
    }


    /**
     * Edit the discount, title, description and more attributes of "proposal-space" relation
     *
     * @param Request $request
     * @param Proposal $proposal
     * @param Space $space
     * @return array
     */
    public function discount(Request $request, Proposal $proposal, Space $space)
    {
        return [
            'success'   => 'true',
            'space'     => $this->proposalFacade->discount($proposal, $space, $request->all()),
            'proposal'  => $proposal,
            'finalProposal' => $this->proposalFacade->getProposalWithSelectedSpaces($proposal)
        ];
    }

    /**
     * @param AddSpaceRequest $request
     * @param Space $space
     * @return array
     */
    public function add(AddSpaceRequest $request, Space $space)
    {
        $this->proposalFacade->addProposalsSpace($space, $request->get('proposals'));
        return ['success' => 'true'];
    }

    /**
     * @param Request $request
     * @param Proposal $proposal
     * @return array
     */
    public function send(Request $request, Proposal $proposal)
    {
        $this->proposalFacade->send($proposal);
        return ['success' => 'true'];
    }

    /**
     * @param Request $request
     * @param Proposal $proposal
     * @param Space $space
     * @return array
     */
    public function selectSpace(Request $request, Proposal $proposal, Space $space)
    {
        $this->proposalFacade->selectSpace($proposal, $space, $request->get('select'));

        if($request->get('select')) {
            return ['success' => 'true', 'message' => 'Espacio seleccionado'];
        }

        return ['success' => 'true', 'message' => 'Espacio desseleccionado'];
    }

    public function observationFile(Request $request, Proposal $proposal)
    {
        if($request->file('observations_file')) {
            $request->file('observations_file')->move('documents/proposals', 'observation-file-' . $proposal->id . '.pdf');
        }

        return redirect()->route('proposals.show', $proposal);
    }

    public function observationFileDelete(Request $request, Proposal $proposal)
    {
        if($proposal->has_observations_file) {
            \File::delete($proposal->observations_file);
        }

        return redirect()->route('proposals.show', $proposal);
    }
}