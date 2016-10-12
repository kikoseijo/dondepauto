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
     * @param Proposal $proposal
     * @return $this
     */
    public function show(Proposal $proposal)
    {
        $proposal->load(['contacts.actions', 'quote.advertiser.contacts.actions']);
        $contacts = $proposal->contacts->sortByDesc('created_at')->all();

        return view('admin.proposals.show')->with([
            'proposal' => $proposal,
            'advertiser' => $proposal->getViewAdvertiser(),
            'contacts' => $contacts
        ]);
    }

    /**
     * @return mixed
     */
    public function search()
    {
        return \Datatables::of($this->advertiserFacade->searchProposals())->make(true);
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
            'proposal'  => $proposal
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
}