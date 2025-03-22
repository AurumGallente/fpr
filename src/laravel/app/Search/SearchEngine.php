<?php

namespace App\Search;

use Illuminate\Support\Collection;
use App\Helpers\ReadabilityHelper;
use App\Models\EStext;
use Exception;
use JsonException;

class SearchEngine
{
    /**
     * @var string
     */
    private string $source;

    /**
     * @var string
     */
    private string $type;

    /**
     * @var string
     */
    private string $text;

    /**
     * @var Collection
     */
    private Collection $chunks;

    const string SRC_SQL = 'src_sql';

    const string SRC_ES = 'src_es';

    const string TYPE_CHUNKS = 'type_chunks';

    const string TYPE_FULLTEXT = 'type_fulltext';

    /**
     * @param string $source
     * @return $this
     * @throws Exception
     */
    public function setSource(string $source): static
    {
        if($source !== self::SRC_SQL && $source !== self::SRC_ES)
        {
            throw new Exception('Invalid source for search engine was provided');
        }
        $this->source = $source;
        return $this;
    }


    /**
     * @param string $type
     * @return $this
     * @throws Exception
     */
    public function setType(string $type): static
    {
        if($type !== self::TYPE_CHUNKS && $type !== self::TYPE_FULLTEXT)
        {
            throw new Exception('Invalid type for search engine was provided');
        }
        $this->type = $type;
        return $this;
    }

    /**
     * @param string $text
     * @return $this
     */
    public function setText(string $text): static
    {
        $this->text = $text;
        return $this;
    }

    /**
     * @param Collection $chunks
     * @return $this
     */
    public function setChunks(Collection $chunks): static
    {
        $this->chunks = $chunks;
        return $this;
    }

    /**
     * @return Collection
     * @throws JsonException
     * @throws Exception
     */
    public function search(): Collection
    {
        $this->checkSetup();
        $result = collect([]);
        switch (true)
        {
            case $this->source === self::SRC_SQL && $this->type === self::TYPE_CHUNKS:
                //
                break;
            case $this->source === self::SRC_ES && $this->type === self::TYPE_CHUNKS:
                    $helper = new ReadabilityHelper($this->text);
                    $chunks = collect($helper->chunking());
                    $EStext = new EStext();
                    $EStext->content = $this->text;
                    $rawResult = $EStext->findSimilarByChunks($chunks)->toArray();
                    $result = collect($EStext->prettySearchResults($rawResult, $this->text));
                break;
            case $this->source === self::SRC_SQL && $this->type === self::TYPE_FULLTEXT:
                throw new Exception('Not implemented');
                break;
            case $this->source === self::SRC_ES && $this->type === self::TYPE_FULLTEXT:
                //todo
                throw new Exception('Not implemented');
                break;
            default:
                throw new Exception('Setup of search engine is wrong');
                break;
        }

        return $result;
    }

    /**
     * @return void
     * @throws Exception
     */
    private function checkSetup(): void
    {
        if(!$this->type)
        {
            throw new Exception('Search type for search engine was not provided');
        }

        if(!$this->source)
        {
            throw new Exception('Search source for search engine was not provided');
        }

        if($this->type === self::TYPE_FULLTEXT && !$this->text)
        {
            throw new Exception('Search text for search engine was not provided');
        }

        if($this->type === self::TYPE_CHUNKS && isset($this->chunks) && $this->chunks->count())
        {
            throw new Exception('Search chunks for search engine were not provided');
        }
    }
}
